<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email',
            'gender' => 'required|string',
            'birthDate' => 'nullable|date',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'password' => 'required|string|min:8',
            'image' => 'nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Nama minimal 6 huruf.',
            'name.required' => 'Name wajib diisi.',
            'name.string' => 'Name format string.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'gender.required' => 'Gender wajib diisi.',
            'birthDate.date' => 'Tanggal lahir menggunakan format tanggal.',
            'address.required' => 'Alamat wajib diisi.',
            'phone_number.required' => 'Nomer telepon wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'image.image' => 'Image harus berupa gambar',
        ];
    }
}
