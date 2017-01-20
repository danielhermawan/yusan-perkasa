<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentReceipt extends Model
{
    use SoftDeletes, CrudTrait;
    protected $fillable = ['employee_id', 'sales_order_id', 'total_payment'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function salesOrder()
    {
        return $this->belongsTo('App\Models\SalesOrder');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withPivot('quantity');
    }

    public function getSoId()
    {
        return $this->salesOrder->id;
    }

    public function getEmployeeName()
    {
        return $this->employee->name;
    }
}
