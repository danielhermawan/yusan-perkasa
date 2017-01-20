<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesOrder extends Model
{
    use SoftDeletes, CrudTrait;

    protected $fillable = ['employee_id', 'customer_id', 'due_date'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withPivot('quantity', 'price', 'status');
    }

    public function PurchaseDemand()
    {
        return $this->belongsTo('App\Models\PurchaseDemand');
    }

    public function getEmployeeName()
    {
        return $this->employee->name;
    }

    public function getCustomerName()
    {
        return $this->customer->name;
    }

    public function deliveryOrders()
    {
        return $this->hasMany('App\Models\DeliveryOrder');
    }
}
