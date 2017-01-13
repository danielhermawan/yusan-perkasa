<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use CrudTrait;

    protected $fillable = ['type_id', 'unit_id', 'name', 'quantity', 'min_quantity', 'max_quantity', 'max_purchase_price',
                            'min_sales_price'];

    public function type()
    {
        return $this->belongsTo('App\Models\ProductType');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function getTypeName()
    {
        return $this->type->name;
    }

    public function getUnitName()
    {
        return $this->unit->name;
    }

    public function suppliers()
    {
        return $this->belongsToMany('App\Models\Supplier')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function customers()
    {
        return $this->belongsToMany('App\Models\Customer')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function purchasingDemands()
    {
        return $this->belongsToMany('App\Models\PurchaseDemand')
            ->withPivot('quantity');
    }

    public function purchaseOrders()
    {
        return $this->belongsToMany('App\Models\PurchaseOrder')
            ->withPivot('quantity', 'price', 'status');
    }
}
