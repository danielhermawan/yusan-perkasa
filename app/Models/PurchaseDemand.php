<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDemand extends Model
{
    use SoftDeletes;
    use CrudTrait;

    protected $fillable = ['employee_id'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withPivot('quantity');
    }

    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder');
    }

    public function getEmployeeName()
    {
        return $this->employee->name;
    }
}
