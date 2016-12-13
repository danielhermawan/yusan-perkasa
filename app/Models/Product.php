<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use CrudTrait;

    protected $fillable = ['type_id', 'unit_id', 'name', 'quantity', 'min_quantity', 'max_quantity', 'min_purchase_prize',
                            'min_sales_prize'];

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
}
