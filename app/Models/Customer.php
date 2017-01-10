<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;
    use CrudTrait;

    protected $fillable = ['name', 'phone', 'faks', 'email', 'address', 'zip_code'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product')
            ->withPivot('price')
            ->withTimestamps();;
    }
}
