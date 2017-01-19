<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderStoreRequest extends FormRequest
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
            'due_date' => 'required|date',
            'supplier_id' => 'required|integer',
            'purchase_demand_id' => 'required|integer',
            'quantity' => 'required|array',
            'product.*' => 'integer|distinct',
            'quantity.*' => 'integer|min:1|add_demand_product:'.$this->purchase_demand_id.','.serialize($this->product),
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
