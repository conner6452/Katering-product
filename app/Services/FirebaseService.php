<?php

namespace App\Services;

use Google\Client;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    public static function sendNotification(string $deviceToken, string $title, string $body, array $data = [])
    {
        try{
            $client = new Client();
            $client->setAuthConfig(base_path(env('FIREBASE_CREDENTIALS')));
            $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

            $accessToken= $client->fetchAccessTokenWithInsertion()['access_token'];
            $id = env('FIREBASE_PROJECT_ID');

            $url = "https://fcm.googleapis.com/v1/projects/{$id}/messages:send";
            $payload = [
                'message' => [
                    'token' => $deviceToken,
                    'notification' => [
                        'title' => $title,
                        'body'  => $body,
                    ],
                    'data' => $data,
                ],
            ];
             $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            if ($response->failed()) {
                \Log::error('FCM send failed', ['response' => $response->body()]);
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('FCM exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => $e->getMessage()];
        
        }
    }
}
