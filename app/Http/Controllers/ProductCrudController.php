<?php

namespace App\Http\Controllers;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\ProductCreateDetailCustomerRequest;
use App\Http\Requests\ProductCreateDetailRequest;
use App\Http\Requests\ProductRequest as StoreRequest;
use App\Http\Requests\ProductRequest as UpdateRequest;
use App\Http\Requests\ProductUpdateDetailCustomerRequest;
use App\Http\Requests\ProductUpdateDetailRequest;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductCrudController extends CrudController  {

	public function setUp() {
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
                'name' => 'max_purchase_price',
                'label' => 'Max Harga Beli'
            ],
            [
                'name' => 'min_sales_price',
                'label' => 'Min Harga Jual'
            ],
            [
                'name' => 'list_supplier',
                'label' => 'List Supplier',
                'type' => 'link',
                'icon' => 'address-card',
                'link' => 'product',
                'link_end' => 'supplier',
                'link_label' => 'Supplier'
            ],
            [
                'name' => 'list_customer',
                'label' => 'List Customer',
                'type' => 'link',
                'icon' => 'user',
                'link' => 'product',
                'link_end' => 'customer',
                'link_label' => 'customer'
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
                'name' => 'max_purchase_price',
                'label' => 'Max Harga Beli',
                'type' => 'number'
            ],
            [
                'name' => 'min_sales_price',
                'label' => 'Min Harga Jual',
                'type' => 'number'
            ]
        ]);

        $this->crud->setDetailColumns('supplier', [
            [
                'name' => 'name',
                'label' => 'Nama'
            ],
            [
                'name' => 'price',
                'label' => 'Harga Supplier',
                'type' => 'pivot'
            ],
            [
                'name' => 'updated_at',
                'label' => 'Update Harga Terakhir',
                'type' => 'pivot'
            ]
        ]);

        $this->crud->setDetailColumns('customer', [
            [
                'name' => 'name',
                'label' => 'Nama'
            ],
            [
                'name' => 'price',
                'label' => 'Harga Customer',
                'type' => 'pivot'
            ],
            [
                'name' => 'updated_at',
                'label' => 'Update Harga Terakhir',
                'type' => 'pivot'
            ]
        ]);

        $this->crud->setDetailCreateFields('supplier', [
            [
                'label' => "Supplier",
                'type' => 'select_from_database',
                'name' => 'supplier', // the method that defines the relationship in your Model
                'model' => 'App\Models\Supplier',
                'allows_null' => false,
                'value' => 'id',
                'display' => 'name'
            ],
            [   // Number
                'name' => 'price',
                'label' => 'Harga',
                'type' => 'number',
                'prefix' => "Rp",
            ],
        ]);

        $this->crud->setDetailCreateFields('customer', [
            [
                'label' => "Customer",
                'type' => 'select_from_database',
                'name' => 'customer', // the method that defines the relationship in your Model
                'model' => 'App\Models\Customer',
                'allows_null' => false,
                'value' => 'id',
                'display' => 'name'
            ],
            [   // Number
                'name' => 'price',
                'label' => 'Harga',
                'type' => 'number',
                'prefix' => "Rp",
            ],
        ]);

        $this->crud->setDetailUpdateFields('supplier', [
            [
                'name' => 'price',
                'label' => 'Harga',
                'type' => 'number',
                'prefix' => "Rp",
                'from' => 'pivot'
            ]
        ]);
        $this->crud->setDetailUpdateFields('customer', [
            [
                'name' => 'price',
                'label' => 'Harga',
                'type' => 'number',
                'prefix' => "Rp",
                'from' => 'pivot'
            ]
        ]);

        $this->crud->setDetailOptions('supplier', [
            'label' => 'supplier',
            'parent_label' => 'product',
            'model' => 'App\Models\Supplier',
            'parentTitleKey' => 'name',
            'relation' => 'suppliers',
            'detail_field' => 'supplier',
            'detail_key' => 'supplier_id',
            'detail_key_title' => 'name'
        ]);
        $this->crud->setDetailOptions('customer', [
            'label' => 'customer',
            'parent_label' => 'product',
            'parentTitleKey' => 'name',
            'relation' => 'customers',
            'detail_field' => 'customer',
            'detail_key' => 'customer_id',
            'detail_key_title' => 'name'
        ]);
    }

    public function getProducts()
    {
        return Product::all();
    }

    public function getSupplierProducts($idSupplier, $idDemand)
    {
        return DB::table('product_supplier')
            ->join('products', 'products.id', '=', 'product_supplier.product_id')
            ->where("supplier_id", $idSupplier)
            ->whereExists(function ($query) use($idDemand){
                $query->select(DB::raw(1))
                    ->from('product_purchase_demand')
                    ->where('purchase_demand_id', $idDemand)
                    ->whereRaw('product_purchase_demand.product_id = product_supplier.product_id');
            })
            ->get();
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}

    public function storeDetail(ProductCreateDetailRequest $request, $id)
    {
        return parent::storeDetailCrud(null, $id);
    }

    public function updateDetail(ProductUpdateDetailRequest $request, $parent_id, $detail_id)
    {
        return parent::updateDetailCrud(null, $parent_id, $detail_id);
    }

    public function storeDetailCustomer(ProductCreateDetailCustomerRequest $request, $id)
    {
        return parent::storeDetailCrud(null, $id);
    }

    public function updateDetailCustomer(ProductUpdateDetailCustomerRequest $request, $parent_id, $detail_id)
    {
        return parent::updateDetailCrud(null, $parent_id, $detail_id);
    }
}
