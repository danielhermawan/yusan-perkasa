<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use CrudTrait;

    public $timestamps = false;
    protected $fillable = ['name'];

    public function Employees()
    {
        return $this->hasMany('App\Models\Employee');
    }
}
