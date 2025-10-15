<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'name' => 'sometimes|string|min:3',
            'email' => [
                'sometimes',
                'email',
                Rule::unique(User::class, 'email')->ignore(
                    $this->route('id') ?? $this->route('user_panel') ?? $this->route('user')
                ),
            ],
            'gender' => 'sometimes|string',
            'birthDate' => 'sometimes|date',
            'address' => 'sometimes|string',
            'phone_number' => 'sometimes|string',
            'password' => 'sometimes|string|min:8',
            'image' => 'sometimes|nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Nama minimal 6.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'image.image' => 'Image harus berupa gambar',
        ];
    }
}
