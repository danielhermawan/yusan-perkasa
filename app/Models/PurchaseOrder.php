<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model
{
    use SoftDeletes, CrudTrait;

    protected $fillable = ['employee_id', 'supplier_id', 'purchase_demand_id', 'due_date'];

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier');
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

    public function getSupplierName()
    {
        return $this->supplier->name;
    }

    public function productReceipts()
    {
        return $this->hasMany('App\Models\ProductReceipt');
    }
}
