<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'required|string|max:255',
            'contact' => 'required|string|min:0',
            'company_detail'=> 'required|min:8',
            'ingredient_id' => 'required|exists:ingredients,id',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string'   => 'Nama harus berupa teks.',
            'name.max'      => 'Nama tidak boleh lebih dari :max karakter.',

            'ingredient_id.required' => 'Supplay perlu diisi.',
            'ingredient_id.exists'   => 'Supplay tidak ditemukan.',

            'contact.required' => 'Kontak wajib diisi.',

            'company_detail.required' => 'Detail wajib diisi.',
            'company_detail.min'       => 'Minimal 8 huruf.',
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
