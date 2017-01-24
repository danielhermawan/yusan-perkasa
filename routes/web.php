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
    Route::get('/', 'DashboardController@purchasing');
    Route::get('/laporan-penjualan', 'DashboardController@sales');
    Route::get('/laporan-pembelian', 'DashboardController@purchasing');
    Route::get('/logout', 'AuthController@logout');

    Route::get('/supplier/{supplier}/product', 'SupplierCrudController@listDetail');
    Route::get('/supplier/{supplier}/product/create', 'SupplierCrudController@createDetail');
    Route::post('/supplier/{supplier}/product', 'SupplierCrudController@storeDetail');
    Route::get('/supplier/{supplier}/product/{product}/edit', 'SupplierCrudController@editDetail');
    Route::put('/supplier/{supplier}/product/{id}', 'SupplierCrudController@updateDetail');
    Route::delete('/supplier/{supplier}/product/{id}', 'SupplierCrudController@destroyDetail');
    Route::get('/get-supplier', 'SupplierCrudController@getSuppliers');

    Route::get('/customer/{customer}/product', 'CustomerCrudController@listDetail');
    Route::get('/customer/{customer}/product/create', 'CustomerCrudController@createDetail');
    Route::post('/customer/{customer}/product', 'CustomerCrudController@storeDetail');
    Route::get('/customer/{customer}/product/{product}/edit', 'CustomerCrudController@editDetail');
    Route::put('/customer/{customer}/product/{id}', 'CustomerCrudController@updateDetail');
    Route::delete('/customer/{customer}/product/{id}', 'CustomerCrudController@destroyDetail');
    Route::get('/get-customer', 'CustomerCrudController@getCustomers');

    CRUD::resource('product', 'ProductCrudController');
    Route::get('/get-product-supplier/{supplier}/{id_demand}', 'ProductCrudController@getSupplierProducts');
    Route::get('/get-product-customer/{customer}', 'ProductCrudController@getCustomerProducts');
    Route::get('/product/{product}/supplier', 'ProductCrudController@listDetail');
    Route::get('/product/{product}/supplier/create', 'ProductCrudController@createDetail');
    Route::post('/product/{product}/supplier', 'ProductCrudController@storeDetail');
    Route::delete('/product/{product}/supplier/{id}', 'ProductCrudController@destroyDetail');
    Route::get('/product/{product}/supplier/{supplier}/edit', 'ProductCrudController@editDetail');
    Route::put('/product/{product}/supplier/{id}', 'ProductCrudController@updateDetail');

    Route::get('/product/{product}/customer', 'ProductCrudController@listDetail');
    Route::get('/getProducts', 'ProductCrudController@getProducts');
    Route::get('/product/{product}/customer/create', 'ProductCrudController@createDetail');
    Route::post('/product/{product}/customer', 'ProductCrudController@storeDetailCustomer');
    Route::delete('/product/{product}/customer/{id}', 'ProductCrudController@destroyDetail');
    Route::get('/product/{product}/customer/{customer}/edit', 'ProductCrudController@editDetail');
    Route::put('/product/{product}/customer/{id}', 'ProductCrudController@updateDetailCustomer');

    CRUD::resource('permintaan-pembelian', 'PurchaseDemandCrudController');
    Route::get('/permintaan-pembelian/{pembelian}/product', 'PurchaseDemandCrudController@listDetail');
    Route::get('/permintaan-pembelian/{pembelian}/product/create', 'PurchaseDemandCrudController@createDetail');
    Route::post('/permintaan-pembelian/{pembelian}/product', 'PurchaseDemandCrudController@storeDetail');
    Route::delete('/permintaan-pembelian/{pembelian}/product/{id}', 'PurchaseDemandCrudController@destroyDetail');
    Route::get('/permintaan-pembelian/{pembelian}/product/{product}/edit', 'PurchaseDemandCrudController@editDetail');
    Route::put('/permintaan-pembelian/{pembelian}/product/{id}', 'PurchaseDemandCrudController@updateDetail');

    CRUD::resource('purchase-order', 'PurchaseOrderCrudController');
    Route::get('/purchase-order/{pembelian}/product', 'PurchaseOrderCrudController@listDetail');
    Route::get('/purchase-order/{pembelian}/product/create', 'PurchaseOrderCrudController@createDetail');
    Route::post('/purchase-order/{pembelian}/product', 'PurchaseOrderCrudController@storeDetail');
    Route::delete('/purchase-order/{pembelian}/product/{id}', 'PurchaseOrderCrudController@destroyDetail');
    Route::get('/purchase-order/{pembelian}/product/{product}/edit', 'PurchaseOrderCrudController@editDetail');
    Route::put('/purchase-order/{pembelian}/product/{id}', 'PurchaseOrderCrudController@updateDetail');

    Route::get('/get-purchase-demand', 'PurchaseOrderCrudController@getPurchaseDemand');
    Route::get('/get-product-po/{id}', 'PurchaseOrderCrudController@getProductPo');
    Route::get('/get-product-po-retur/{id}', 'PurchaseOrderCrudController@getProductPoForRetur');
    Route::get('/get-purchase-order', 'PurchaseOrderCrudController@getPo');

    Route::get('/get-product-so/{id}', 'SalesOrderCrudController@getProductSo');
    Route::get('/get-delivery-order', 'DeliveryOrderCrudController@getDo');
    Route::get('/get-product-so-retur/{id}', 'SalesOrderCrudController@getProductSoForRetur');
    Route::get('/get-sales-order', 'SalesOrderCrudController@getSo');

    CRUD::resource('penerimaan-barang', 'ProductReceiptCrudController');
    Route::get('/penerimaan-barang/{productReceipt}/product', 'ProductReceiptCrudController@listDetail');

    CRUD::resource('retur-pembelian', 'PurchaseReturnCrudController');
    Route::get('/retur-pembelian/{productReceipt}/product', 'PurchaseReturnCrudController@listDetail');

    CRUD::resource('pembayaran-pembelian', 'PurchasePaymentCrudController');

    CRUD::resource('sales-order', 'SalesOrderCrudController');
    Route::get('/sales-order/{salesorder}/product', 'SalesOrderCrudController@listDetail');

    CRUD::resource('delivery-order', 'DeliveryOrderCrudController');
    Route::get('/delivery-order/{deliveryorder}/product', 'DeliveryOrderCrudController@listDetail');
    Route::get('/get-product-do/{id}', 'DeliveryOrderCrudController@getDo');

    CRUD::resource('retur-penjualan', 'SalesReturnCrudController');
    Route::get('/retur-penjualan/{rp}/product', 'SalesReturnCrudController@listDetail');

    CRUD::resource('penerimaan-pembayaran', 'PaymentReceiptCrudController');

    CRUD::resource('role', 'RoleCrudController');
    CRUD::resource('employee', 'EmployeeCrudController');
    CRUD::resource('unit', 'UnitCrudController');
    CRUD::resource('product-type', 'ProductTypeCrudController');
    CRUD::resource('supplier', 'SupplierCrudController');
    CRUD::resource('customer', 'CustomerCrudController');
});

//todo: create po menu from p.demand
//todo: cannot edit or delete demand and po(and their details) when penerimaan, pembayaran created
//todo: validation give warning permitaan pembelian maximal quantity
//todo: notification when due date
//todo: old value in add product in puchase demand
//todo: give prefix add when add quantity of product
//todo: create detail product ada min/max sales/beli
//todo:route base role
//todo: print
//todo:add more filter
//todo: create po from purchase demand
//todo:format rupiah dan date
//todo: link in column each transaction that corellate
//todo: after all po done status po done