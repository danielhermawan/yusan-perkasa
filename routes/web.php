<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'guest'], function(){
    Route::get('/login', 'AuthController@index');
    Route::post('/login', 'AuthController@login');
});

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', 'EmployeeCrudController@index');
    Route::get('/logout', 'AuthController@logout');

    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('employee', 'EmployeeCrudController');
    CRUD::resource('product', 'ProductCrudController');
    CRUD::resource('unit', 'UnitCrudController');
    CRUD::resource('product-type', 'ProductTypeCrudController');
});

//todo:validation js
//todo:ajax js
//todo:breacrumb
//todo: fix active menu