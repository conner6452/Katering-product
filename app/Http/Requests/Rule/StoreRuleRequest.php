<?php

namespace App\Http\Requests\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreRuleRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'  => 'required|string|max:255',
            'capacity' => 'required|integer|min:0',
            'budget' => 'required|integer|min:0',
            'description' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Field nama harus diisi',
            'name.max' => 'Field nama maksimal mengandung 255 karakter',

            'capacity.required' => 'Field kapasitas harus diisi',
            'capacity.integer' => 'Kapasitas harus berupa integer',
            'capacity.min' => 'Kapasitas tidak boleh 0',

            'budget.required' => 'Field Budget harus diisi',
            'budget.integer' => 'Budget harus berupa integer',
            'budget.min' => 'Budget tidak boleh 0',

            'description.required' => 'Field deskripsi harus diisi',
        ];
    }
}
