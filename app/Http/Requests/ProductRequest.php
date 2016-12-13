<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProductRequest extends \Backpack\CRUD\app\Http\Requests\CrudRequest
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
            'name' => 'required|min:3|max:255',
            'type_id' => 'required|integer',
            'unit_id' => 'required|integer',
            'quantity' => 'required|integer|between:'.$this->input('min_quantity').','.$this->input('max_quantity'),
            'min_quantity' => 'required|integer',
            'max_quantity' => 'required|integer',
            'min_purchase_price' => 'required|numeric',
            'min_sales_price' => 'required|numeric',
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
            'quantity.between' => 'Quantity must be between min and max quantity'
        ];
    }
}