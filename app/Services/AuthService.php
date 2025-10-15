<?php

namespace App\Services;

use App\Contracts\Interface\AuthInterface;
use App\Http\Resources\DefaultResource;
use App\Mail\ForgotPasswordMail;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService
{
    protected $authInterface;
    public function __construct(AuthInterface $authInterface)
    {
        $this->authInterface = $authInterface;
    }

    public function forgotPassword(string $email)
    {
        $user = $this->authInterface->forgotPassword($email);

        if (! $user) {
            return DefaultResource::make(['code' => 404, 'message' => 'Email tidak ditemukan'])->response()->setStatusCode(404);
        }

        $token = Str::random(64);

        PasswordResetToken::updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now(),
            ]
        );

        Mail::to($email)->send(new ForgotPasswordMail($token, $email));

        return true;
    }
}
