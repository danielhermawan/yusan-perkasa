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

    Route::get('/customer/{customer}/product', 'CustomerCrudController@listDetail');
    Route::get('/customer/{customer}/product/create', 'CustomerCrudController@createDetail');
    Route::post('/customer/{customer}/product', 'CustomerCrudController@storeDetail');
    Route::get('/customer/{customer}/product/{product}/edit', 'CustomerCrudController@editDetail');
    Route::put('/customer/{customer}/product/{id}', 'CustomerCrudController@updateDetail');
    Route::delete('/customer/{customer}/product/{id}', 'CustomerCrudController@destroyDetail');

    CRUD::resource('product', 'ProductCrudController');

    Route::get('/product/{product}/supplier', 'ProductCrudController@listDetail');
    Route::get('/product/{product}/supplier/create', 'ProductCrudController@createDetail');
    Route::post('/product/{product}/supplier', 'ProductCrudController@storeDetail');
    Route::delete('/product/{product}/supplier/{id}', 'ProductCrudController@destroyDetail');
    Route::get('/product/{product}/supplier/{supplier}/edit', 'ProductCrudController@editDetail');
    Route::put('/product/{product}/supplier/{id}', 'ProductCrudController@updateDetail');

    Route::get('/product/{product}/customer', 'ProductCrudController@listDetail');
    Route::get('/product/{product}/customer/create', 'ProductCrudController@createDetail');
    Route::post('/product/{product}/customer', 'ProductCrudController@storeDetailCustomer');
    Route::delete('/product/{product}/customer/{id}', 'ProductCrudController@destroyDetail');
    Route::get('/product/{product}/customer/{customer}/edit', 'ProductCrudController@editDetail');
    Route::put('/product/{product}/customer/{id}', 'ProductCrudController@updateDetailCustomer');

    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('employee', 'EmployeeCrudController');
    CRUD::resource('unit', 'UnitCrudController');
    CRUD::resource('product-type', 'ProductTypeCrudController');
    CRUD::resource('supplier', 'SupplierCrudController');
    CRUD::resource('customer', 'CustomerCrudController');
});

//todo:validation js?
//todo:ajax js email?
//todo: fix active menu?
//todo:nfs?
//todo: select from database in resource
//todo: create detail product ada min/max sales/beli
//todo:change view for detail so making detail variable?
//todo:route base role
//todo:add more filter
//todo:format rupiah dan date
//todo:crudweb route with detail
//todo:ubah beberapa opsi di detail function yang mengambil dari nama rute dingati ambil dari option