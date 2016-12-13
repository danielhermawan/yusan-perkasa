<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Backpack\CRUD\CrudTrait;

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
}
