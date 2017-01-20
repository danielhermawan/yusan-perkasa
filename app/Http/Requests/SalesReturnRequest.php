<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SalesReturnRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
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
            'description' => 'required|string',
            'delivery_order_id' => 'required|integer',
            'quantity' => 'required|array',
            'product.*' => 'integer|distinct',
            'quantity.*' => 'integer|min:1|add_sales_retur_product:'.$this->delivery_order_id.','.serialize($this->product).'|check_stock:'.serialize($this->product)
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'product.*.distinct' => 'The product field has a duplicate value.',
            'quantity.*.min' => 'Minimal quantity of product must be 1'
        ];
    }
}