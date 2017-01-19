<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 1/10/2017
 * Time: 12:27 PM
 */

namespace App\Http\Controllers;


use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\PurchaseDemandRequest;
use App\Http\Requests\PurchaseDemandStoreDetailRequest;
use App\Http\Requests\PurchaseDemandUpdateDetailRequest;
use App\Models\PurchaseDemand;
use Auth;

class PurchaseDemandCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\PurchaseDemand");
        $this->crud->setRoute("permintaan-pembelian");
        $this->crud->setEntityNameStrings('Permintaan Pembelian', 'Permintaan Pembelian');
        $this->crud->removeButton('update');
        $this->crud->redirectEdit = false;

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'ID'
            ],
            [
                'name' => 'created_at',
                'label' => 'Tanggal Pembelian'
            ],
            [
                'label' => 'Created by',
                'type' => 'model_function',
                'function_name' => 'getEmployeeName'
            ],
            [
                'name' => 'list_product',
                'label' => 'List Product',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'permintaan-pembelian',
                'link_end' => 'product',
                'link_label' => 'Product'
            ]
        ]);
        $this->crud->addFields([
            [
                'name' => 'product',
                'type' => 'add_product',
                'label' => 'Product'
            ],
        ]);
        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'Permintaan ID',
            'model' => 'App\Models\Product',
            'parentTitleKey' => 'id',
            'relation' => 'products',
            'detail_field' => 'product',
            'detail_key' => 'product_id',
            'detail_key_title' => 'name'
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
                'name' => 'max_quantity',
                'label' => 'Max Quantity'
            ],
            [
                'name' => 'quantity',
                'label' => 'Qty di Gudang'
            ],
            [
                'name' => 'quantity',
                'label' => 'Quantity diminta',
                'type' => 'pivot'
            ],
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
                'name' => 'list_supplier',
                'label' => 'List Supplier',
                'type' => 'link',
                'icon' => 'address-card',
                'link' => 'product',
                'link_end' => 'supplier',
                'link_label' => 'Supplier'
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
                'name' => 'quantity',
                'label' => 'Quantity',
                'type' => 'number',
                'attributes' => [
                    'min' => 1
                ],
            ],
        ]);
        $this->crud->setDetailUpdateFields('product', [
            [   // Number
                'name' => 'quantity',
                'label' => 'Quantity',
                'type' => 'number',
                'attributes' => [
                    'min' => 1
                ],
                'from' => 'pivot'
            ],
        ]);
    }

    public function store(PurchaseDemandRequest $request)
    {
        $this->crud->hasAccessOrFail('create');

        // fallback to global request instance
        if (is_null($request)) {
            $request = \Request::instance();
        }

        // replace empty values with NULL, so that it will work with MySQL strict mode on
        foreach ($request->input() as $key => $value) {
            if (empty($value) && $value !== '0') {
                $request->request->set($key, null);
            }
        }

        $demand = new PurchaseDemand;
        $demand->employee_id = Auth::id();
        $demand->save();
        $data = [];
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $data[$p] = ['quantity' => $q];
        }
        $demand->products()->sync($data);
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return \Redirect::to($this->crud->route.'/'.$demand->getKey().'/edit');
            default:
                return \Redirect::to($request->input('redirect_after_save'));
        }

    }

    public function getPurchaseDemand()
    {
        return PurchaseDemand::all();
    }

    public function storeDetail(PurchaseDemandStoreDetailRequest $request, $id)
    {
        return parent::storeDetailCrud(null, $id);
    }

    public function updateDetail(PurchaseDemandUpdateDetailRequest $request, $parent_id, $detail_id)
    {
        return parent::updateDetailCrud(null, $parent_id, $detail_id);
    }
}