<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ប្តូរមកប្រើ ShouldBroadcastNow

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QrLoginSuccessful implements ShouldBroadcastNow // ប្រើ Now ដើម្បីឱ្យវាផ្ញើភ្លាមៗដោយមិនបាច់ចាំ Queue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $token;
    public $userId;

    /**
     * បង្កើត Event instance ថ្មី
     */
    public function __construct($token, $userId)
    {
        $this->token = $token;
        $this->userId = $userId;
    }

    /**
     * កំណត់ Channel ដែលត្រូវផ្ញើទៅ (Public Channel)
     */
    public function broadcastOn(): array
    {
        // ត្រូវប្រាកដថាឈ្មោះ Channel នេះត្រូវគ្នានឹង JavaScript ក្នុង login.blade.php
        return [new Channel('login-channel-' . $this->token)];
    }

    /**
     * កំណត់ឈ្មោះ Event (Alias) ដើម្បីឱ្យ JavaScript ងាយស្រួលស្ដាប់
     */
    public function broadcastAs(): string
    {
        return 'login-success';
    }

    /**
     * ទិន្នន័យដែលត្រូវផ្ញើទៅកាន់ Pusher
     */
    public function broadcastWith(): array
    {
        return [
            'token' => $this->token,
            'userId' => $this->userId,
        ];
    }
}