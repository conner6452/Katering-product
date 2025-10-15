<?php

namespace App\Http\Requests\Ingredient;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIngredientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'sometimes|string|max:255',
            'stock' => 'sometimes|integer|min:0',
            'detail'=> 'sometimes|in:kg,liter,pack',
            'price' => 'sometimes|numeric|min:0',
            'image' => 'nullable|mimes:png,jpg,webp|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'name.string'   => 'Nama bahan harus berupa teks.',
            'name.max'      => 'Nama bahan tidak boleh lebih dari :max karakter.',

            'stock.integer'  => 'Stok harus berupa angka bulat.',
            'stock.min'      => 'Stok tidak boleh kurang dari :min.',

            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'   => 'Ukuran gambar maksimal 2MB.',

            'detail.in'       => 'Satuan tidak valid. Pilih salah satu: kg, liter, pack.',
        ];
    }

    public function attributes()
    {
        return [
            'name'  => 'nama bahan',
            'stock' => 'stok',
            'detail'=> 'satuan',
        ];
    }
}