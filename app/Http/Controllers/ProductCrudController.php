<?php

namespace App\Http\Controllers;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;

class ProductCrudController extends CrudController  {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Product");
        $this->crud->setRoute("product");
        $this->crud->setEntityNameStrings('product', 'products');

        $this->crud->setColumns(['name','quantity']);
        $this->crud->addColumns([
            [
                'label' => 'Tipe',
                'type' => 'model_function',
                'function_name' => 'getTypeName'
            ],
            [
                'label' => 'Unit',
                'type' => 'model_function',
                'function_name' => 'getUnitName'
            ],
            [
                'name' => 'min_quantity',
                'label' => 'Min Qty'
            ],
            [
                'name' => 'max_quantity',
                'label' => 'Max Qty'
            ],
            [
                'name' => 'min_purchase_price',
                'label' => 'Min Harga Beli'
            ],
            [
                'name' => 'min_sales_price',
                'label' => 'Min Harga Jual'
            ]/*,
            [
                'label' => "Supplier", // Table column heading
                'type' => "select_multiple",
                'name' => 'suppliers', // the method that defines the relationship in your Model
                'entity' => 'suppliers', // the method that defines the relationship in your Model
                'attribute' => "name", // foreign key attribute that is shown to user
                'model' => "App\Models\Supplier", // foreign key model
            ]*/
        ]);
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => 'Nama Produk'
            ],
            [
                'label' => "Tipe",
                'type' => 'select2',
                'name' => 'type_id',
                'entity' => 'type',
                'attribute' => 'name',
                'model' => "App\Models\ProductType"
            ],
            [
                'label' => "Unit",
                'type' => 'select2',
                'name' => 'unit_id',
                'entity' => 'unit',
                'attribute' => 'name',
                'model' => "App\Models\Unit"
            ],
            [
                'name' => 'quantity',
                'label' => 'Qty',
                'type' => 'number'
            ],
            [
                'name' => 'min_quantity',
                'label' => 'Min Qty',
                'type' => 'number'
            ],
            [
                'name' => 'max_quantity',
                'label' => 'Max Qty',
                'type' => 'number'
            ],
            [
                'name' => 'min_purchase_price',
                'label' => 'Min Harga Beli',
                'type' => 'number'
            ],
            [
                'name' => 'min_sales_price',
                'label' => 'Min Harga Jual',
                'type' => 'number'
            ]/*,
            [
                'label' => "Supplier",
                'type' => 'select2_multiple',
                'name' => 'suppliers', // the method that defines the relationship in your Model
                'entity' => 'suppliers', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'model' => "App\Models\Supplier", // foreign key model
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
            ]*/
        ]);
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}


}
