<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use CrudTrait;

    protected $guarded = ['remember_token', 'created_at', 'updated_at', 'deleted_at'];

    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function getRoleName()
    {
       return $this->role->name;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function purchaseDemands()
    {
        return $this->hasMany('App\Models\PurchaseDemand');
    }

    public function purchaseOrders()
    {
        return $this->hasMany('App\Models\PurchaseOrder');
    }

    public function productReceipts()
    {
        return $this->hasMany('App\Models\ProductReceipt');
    }
}
