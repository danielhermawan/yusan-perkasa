<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseDemandStoreDetailRequest extends FormRequest
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

    public function rules()
    {
        return [
            'product' => [
                'required',
                'integer',
                Rule::unique('product_purchase_demand', 'product_id')->where(function ($query) {
                    $query->where('purchase_demand_id', explode('/',$this->path())[1]);
                })
            ],
            'quantity' => 'required|integer|min:1'
        ];
    }

}
