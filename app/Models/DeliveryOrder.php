<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model
{
    use SoftDeletes, CrudTrait;
    protected $fillable = ['employee_id', 'sales_order_id', 'sending_date'];

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
        return $this->purchaseOrder->id;
    }

    public function getEmployeeName()
    {
        return $this->employee->name;
    }
}
