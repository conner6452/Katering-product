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

        ];
    }
}
