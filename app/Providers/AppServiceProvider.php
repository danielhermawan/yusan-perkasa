<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('check_price', function ($attribute, $value, $parameters, $validator) {
            $valid = true;
            $model = new $parameters[0]();
            $products = explode('-', $parameters[1]);
            $column = $parameters[2];
            $condition = $parameters[3];
            foreach ($products as $v){
                $tablePrice = $model->find($v)->{$column};
                if($condition === 'min')
                    $valid = intval($value) >= $tablePrice;
                else if($condition === 'max')
                    $valid = intval($value) <= $tablePrice;
                else
                    $valid = false;
            }
            return $valid;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
