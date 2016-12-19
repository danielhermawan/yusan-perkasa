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

    Route::get('/supplier/{supplier}/product', 'SupplierCrudController@listDetail');
    Route::get('/supplier/{supplier}/product/create', 'SupplierCrudController@createDetail');
    Route::post('/supplier/{supplier}/product', 'SupplierCrudController@storeDetail');
    Route::get('/supplier/{supplier}/product/{product}/edit', 'SupplierCrudController@editDetail');
    Route::put('/supplier/{supplier}/product/{id}', 'SupplierCrudController@updateDetail');
    Route::delete('/supplier/{supplier}/product/{id}', 'SupplierCrudController@destroyDetail');

    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('employee', 'EmployeeCrudController');
    CRUD::resource('product', 'ProductCrudController');
    CRUD::resource('unit', 'UnitCrudController');
    CRUD::resource('product-type', 'ProductTypeCrudController');
    CRUD::resource('supplier', 'SupplierCrudController');
    CRUD::resource('customer', 'CustomerCrudController');
});

//todo:validation js
//todo:ajax js email
//todo: fix active menu
//todo:nfs
//todo:route base role
//todo:add more filter
//todo:create detail exclude already has
//todo:format rupiah
//todo:validation detail
//todo:change view for detail so making detail variable
//todo:crudweb route with detail
//todo:ubah beberapa opsi di detail function yang mengambil dari nama rute dingati ambil dari option