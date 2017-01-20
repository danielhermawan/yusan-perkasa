<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use SoftDeletes, CrudTrait;
    protected $fillable = ['employee_id', 'delivery_order_id', 'description'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function deliveryOrder()
    {
        return $this->belongsTo('App\Models\DeliveryOrder');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withPivot('quantity', 'status');
    }

    public function getDoId()
    {
        return $this->deliveryOrder->id;
    }

    public function getEmployeeName()
    {
        return $this->employee->name;
    }
}
