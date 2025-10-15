<?php

namespace App\Http\Requests\Ingredient;

use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'detail'=> 'required|in:kg,liter,pack',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|mimes:png,jpg,webp|max:2048'

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama bahan wajib diisi.',
            'name.string'   => 'Nama bahan harus berupa teks.',
            'name.max'      => 'Nama bahan tidak boleh lebih dari :max karakter.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer'  => 'Stok harus berupa angka bulat.',
            'stock.min'      => 'Stok tidak boleh kurang dari :min.',
            
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'image.max'   => 'Ukuran gambar maksimal 2MB.',

            'price.required' => 'Harga wajib diisi.',
            'price.numeric' => 'Harga berupa numerik.',

            'detail.required' => 'Satuan/tipe detail wajib dipilih.',
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
