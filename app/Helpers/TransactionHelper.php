<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionHelper
{
    /**
     * Generate signature untuk transaksi baru
     */
    public static function generateSignature(string $merchantRef, int $amount): string
    {
        $privateKey   = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');

        return hash_hmac('sha256', $merchantCode . $merchantRef . $amount, $privateKey);
    }

    /**
     * Validasi signature dari callback Tripay
     */
    public static function validateCallback(Request $request): bool
    {
        $json = $request->getContent();

        $signature = hash_hmac(
            'sha256',
            $json,
            config('services.tripay.private_key')
        );

        return hash_equals(
            $signature,
            $request->header('X-Callback-Signature')
        );
    }

    /**
     * Ambil daftar channel pembayaran aktif dari Tripay
     */
    public static function getPaymentChannels(): array
    {
        $apiKey  = config('services.tripay.api_key');
        $baseUrl = rtrim(config('services.tripay.base_url', 'https://tripay.co.id/api-sandbox'), '/');

        $url = $baseUrl . '/merchant/payment-channel';

        $response = Http::withToken($apiKey)->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => 'Gagal mengambil channel pembayaran',
            'data'    => $response->body(),
        ];
    }
}
