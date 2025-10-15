<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'sometimes|string|max:255',
            'contact' => 'sometimes|string|min:0',
            'company_detail'=> 'sometimes|min:8',
            'ingredient_id' => 'sometimes|exists:ingredients,id',

        ];
    }

    public function messages()
    {
        return [
            'name.string'   => 'Nama harus berupa teks.',
            'name.max'      => 'Nama tidak boleh lebih dari :max karakter.',
            'company_detail.min'       => 'Minimal 8 huruf.',
            
            'ingredient_id.exists'   => 'Kategori tidak ditemukan.',
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
