<?php
namespace App\Notifications\Channels;

use App\Models\Notification;
use Illuminate\Support\Facades\Http;

class FcmChannel
{
    public function send($notifiable, Notification $notification)
    {
        $fcmToken = $notifiable -> fcm_token;
        if(!$fcmToken)return;
        $data = $notifiable->toFcm($notifiable);

        Http::withHeaders([
            'Authorization' => 'keys' . config('service.fcm.server_key'),
            'Content-Type' => 'Application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', [
        'to' => $fcmToken,
        'notification' =>[
            'title' => $data['title'],
            'message' => $data['message'],
        ],
        'data' => $data
    ]);
    }
}