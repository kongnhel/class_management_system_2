<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
// នៅក្នុង TelegramController.php
public function handleWebhook(Request $request)
{
    $chatId = $request->input('message.chat.id');
    $text = $request->input('message.text'); // វាលោតចេញមក "/start 1" (ប្រសិនបើ User ID = 1)

    if (str_contains($text, '/start')) {
        $userId = str_replace('/start ', '', $text);
        
        $user = User::find($userId);
        if ($user) {
            $user->telegram_chat_id = $chatId; // រក្សាទុក Chat ID ចូលក្នុង Table Users
            $user->save();
            
            // ផ្ញើសារតបទៅសិស្សវិញថាជោគជ័យ
            $this->notifyTelegram($chatId, "✅ ការភ្ជាប់គណនីជោគជ័យ! អ្នកនឹងទទួលបានពិន្ទុតាមរយៈ Bot នេះ។");
        }
    }
}
    private function sendReply($chatId, $message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text'    => $message,
        ]);
    }


    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            // ១. ទាញយកសាស្ត្រាចារ្យទាំងឡាយណាដែលមាន Telegram Chat ID
            $users = User::whereNotNull('telegram_chat_id')->get();
            $botToken = env('TELEGRAM_BOT_TOKEN2'); // កុំភ្លេចដាក់ក្នុង .env

            foreach ($users as $user) {
                // ២. ទាញយកកាលវិភាគថ្ងៃនេះរបស់សាស្ត្រាចារ្យម្នាក់ៗ
                // លោកគ្រូត្រូវកែសម្រួល Logic ទាញកាលវិភាគតាម Database របស់លោកគ្រូ
                $todaySchedules = \App\Models\Schedule::where('professor_id', $user->id)
                    ->whereDate('date', now())
                    ->orderBy('start_time', 'asc')
                    ->get();

                if ($todaySchedules->isNotEmpty()) {
                    $message = "📅 <b>ជម្រាបសួរលោកគ្រូ " . ($user->profile->full_name_km ?? $user->name) . "</b>\n";
                    $message .= "នេះគឺជាកាលវិភាគបង្រៀនរបស់លោកគ្រូសម្រាប់ថ្ងៃនេះ៖\n\n";

                    foreach ($todaySchedules as $index => $item) {
                        $num = $index + 1;
                        $message .= "{$num}. <b>{$item->subject_name}</b>\n";
                        $message .= "   ⏰ ម៉ោង: {$item->start_time} - {$item->end_time}\n";
                        $message .= "   📍 បន្ទប់: {$item->room_name}\n";
                        $message .= "--------------------------\n";
                    }
                    
                    $message .= "\nសូមលោកគ្រូត្រៀមខ្លួនឱ្យបានរួចរាល់។ សូមអរគុណ!";

                    // ៣. ផ្ញើសារទៅកាន់ Telegram
                    Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                        'chat_id' => $user->telegram_chat_id,
                        'text' => $message,
                        'parse_mode' => 'HTML',
                    ]);
                }
            }
        })->dailyAt('07:00');
    }
}