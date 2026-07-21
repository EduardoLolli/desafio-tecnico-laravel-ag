<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'code'          => 'required|string|unique:products,code',
            'name'          => 'required|string|max:130',
            'description'   => 'nullable|string|max:450',
            'price'         => 'required|numeric|min:0',
            'stock_qtt'     => 'required|integer',
            'barcode'       => 'nullable|string|max:100|unique:products,barcode',
            'family_code'   => [
                'required',
                'string',
                Rule::exists('product_families', 'code')
            ],
        ];
    }
}
