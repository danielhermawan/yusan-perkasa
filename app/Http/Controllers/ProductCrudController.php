<?php

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;

class ProductCrudController extends CrudController {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\Product");
        $this->crud->setRoute("product");
        $this->crud->setEntityNameStrings('product', 'products');

        $this->crud->setColumns(['name','qty']);
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
            ]
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
                'model' => "App\Models\Type"
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
            ]
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
