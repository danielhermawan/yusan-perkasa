<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/13/2016
 * Time: 4:05 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\SupplierCreateDetailRequest;
use App\Http\Requests\SupplierRequest;
use App\Http\Requests\SupplierUpdateDetailRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;

class SupplierCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\Supplier");
        $this->crud->setRoute("supplier");
        $this->crud->setEntityNameStrings('supplier', 'suppliers');

        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => 'Nama'
            ],
            [
                'name' => 'phone',
                'label' => 'Phone'
            ],
            [
                'name' => 'email',
                'label' => 'Email'
            ],
            [
                'name' => 'address',
                'label' => 'Alamat'
            ],
            [
                'name' => 'zip_code',
                'label' => 'Kode Pos'
            ],
            [
                'name' => 'list_barang',
                'label' => 'List Barang',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'supplier',
                'link_end' => 'product',
                'link_label' => 'Barang'
            ]
        ]);
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => "Nama Supplier"
            ],
            [
                'name' => 'phone',
                'label' => "Phone Number"
            ],
            [
                'name' => 'email',
                'label' => 'Email',
                'type' => 'email'
            ],
            [
                'name' => 'address',
                'label' => 'Alamat',
                'type' => 'address'
            ],
            [
                'name' => 'zip_code',
                'label' => 'Kode Pos'
            ]
        ]);

        $this->crud->setDetailColumns('product', [
            [
                'name' => 'name',
                'label' => 'Nama'
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
        $this->crud->setDetailCreateFields('product', [
            [
                'label' => "Product",
                'type' => 'select_from_database',
                'name' => 'product', // the method that defines the relationship in your Model
                'model' => 'App\Models\Product',
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
        $this->crud->setDetailUpdateFields('product', [
            [
                'name' => 'price',
                'label' => 'Harga',
                'type' => 'number',
                'prefix' => "Rp",
                'from' => 'pivot'
            ]
        ]);
        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'supplier',
            'parentTitleKey' => 'name',
            'relation' => 'products',
            'detail_field' => 'product',
            'detail_key' => 'product_id',
            'detail_key_title' => 'name'
        ]);

    }

    public function getSuppliers()
    {
        return Supplier::all();
    }

    public function store(SupplierRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(SupplierUpdateRequest $request)
    {
        return parent::updateCrud();
    }

    public function storeDetail(SupplierCreateDetailRequest $request, $id)
    {
        return parent::storeDetailCrud(null, $id);
    }

    public function updateDetail(SupplierUpdateDetailRequest $request, $parent_id, $detail_id)
    {
        return parent::updateDetailCrud(null, $parent_id, $detail_id);
    }
}