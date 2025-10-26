<?php

namespace App\Http\Requests\PreOrder;

use Illuminate\Foundation\Http\FormRequest;

class StorePreOrderRequest extends FormRequest
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
            'supplier_id' => 'required', 'uuid', 'exists:suppliers,id',
            'ingredient_id' => 'required', 'uuid', 'exists:ingredients,id',
            'quantity' => 'required', 'integer', 'min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier wajib diisi.',
            'supplier_id.uuid' => 'Format supplier tidak valid.',
            'supplier_id.exists' => 'Supplier tidak ditemukan.',
            'ingredient_id.required' => 'Bahan wajib diisi.',
            'ingredient_id.uuid' => 'Format bahan tidak valid.',
            'ingredient_id.exists' => 'Bahan tidak ditemukan.',
            'quantity.required' => 'Jumlah bahan wajib diisi.',
            'quantity.integer' => 'Jumlah bahan harus berupa angka.',
            'quantity.min' => 'Jumlah bahan minimal 1.',
        ];
    }
}
