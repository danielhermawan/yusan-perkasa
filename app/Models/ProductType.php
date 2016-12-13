<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use CrudTrait;

    public $timestamps = false;
    protected $fillable = ['name'];

    public function Products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
