<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'sometimes|string|max:130',
            'description'   => 'sometimes|string|max:450',
            'price'         => 'sometimes|numeric|min:0',
            'stock_qtt'     => 'sometimes|integer',
            'barcode'       => 'sometimes|string|max:100|unique:products,barcode',
            'family_code'   => [
                'sometimes',
                'string',
                Rule::exists('product_families', 'code')
            ],
        ];
    }
}
