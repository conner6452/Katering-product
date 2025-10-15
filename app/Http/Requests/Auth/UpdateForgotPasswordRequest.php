<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdateForgotPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
            'token' => 'required|exists:password_reset_tokens,token'
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Field password harus diisi',
            'password.min' => 'Password harus berisi setidaknya 8 karakter',

            'confirm_password.required' => 'Field konfirmasi password harus diisi',
            'confirm_password.same' => 'Konfirmasi password tidak sama dengan password, periksa lagi',

            'token.required' => 'Token harus ada',
            'token.exists' => 'Token tidak valid, coba lagi'
        ];
    }
}
