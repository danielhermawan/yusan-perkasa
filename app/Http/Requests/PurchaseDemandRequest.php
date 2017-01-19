<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseDemandRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product' => 'required|array',
            'quantity' => 'required|array',
            'product.*' => 'integer|distinct',
            'quantity.*' => 'integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'product.*.distinct' => 'The product field has a duplicate value.',
            'quantity.*.min' => 'Minimal quantity of product must be 1'
        ];
    }
}
