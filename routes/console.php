<?php

use App\Models\User;
use App\Models\Schedule as ClassSchedule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;

Schedule::call(function () {
    // ១. ទាញយក User ដែលមាន Telegram Chat ID
    $users = User::whereNotNull('telegram_chat_id')->get();
    $botToken = env('TELEGRAM_BOT_TOKEN2');
    $todayName = Carbon::now()->format('l'); // លទ្ធផល: Thursday
$users = User::where('role', 'professor')
                 ->whereNotNull('telegram_chat_id')
                 ->get();

    foreach ($users as $user) {
        // ២. ទាញយកកាលវិភាគដោយប្រើ lecturer_user_id តាមការរកឃើញក្នុង Tinker
        $todaySchedules = ClassSchedule::with(['courseOffering.course', 'room'])
            ->whereHas('courseOffering', function ($query) use ($user) {
                // កែពី user_id ឬ professor_id មកជា lecturer_user_id វិញ
                $query->where('lecturer_user_id', $user->id); 
            })
            ->where('day_of_week', $todayName)
            ->orderBy('start_time', 'asc')
            ->get();

        if ($todaySchedules->isNotEmpty()) {
            $message = "📅 <b>កាលវិភាគបង្រៀនថ្ងៃនេះ (" . $todayName . ")</b>\n";
            $message .= "សាស្ត្រាចារ្យ៖ <b>" . $user->name . "</b>\n\n";

            foreach ($todaySchedules as $index => $item) {
                $num = $index + 1;
                $start = Carbon::parse($item->start_time)->format('h:i A');
                $end = Carbon::parse($item->end_time)->format('h:i A');
                
                $subject = $item->courseOffering->course->title_en ?? 'N/A';
                $room = $item->room->room_name ?? 'បន្ទប់ ' . $item->room_number;

                $message .= "{$num}. <b>{$subject}</b>\n";
                $message .= "   ⏰ ម៉ោង: {$start} - {$end}\n";
                $message .= "   📍 បន្ទប់: {$room}\n";
                $message .= "--------------------------\n";
            }
            
            $message .= "\nសូមលោកគ្រូអញ្ជើញបង្រៀនដោយរីករាយ! 🙏";

            Http::post("https://api.telegram.org/bot{$botToken}/sendMessage", [
                'chat_id' => $user->telegram_chat_id,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
        }
    }
})->dailyAt('07:00') 
  ->timezone('Asia/Phnom_Penh');