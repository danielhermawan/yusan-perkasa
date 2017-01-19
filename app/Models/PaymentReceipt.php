<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReceipt extends Model
{
    use SoftDeletes, CrudTrait;
    protected $fillable = ['employee_id', 'purchase_order_id', 'receiving_date'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo('App\Models\PurchaseOrder');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withPivot('quantity');
    }

    public function getPoId()
    {
        return $this->purchaseOrder->id;
    }

    public function getEmployeeName()
    {
        return $this->employee->name;
    }
}
