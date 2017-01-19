<?php

namespace App\Http\Controllers;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Events\PurchaseOrderModified;
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\PurchaseReturnRequest;
use App\Http\Requests\PurchaseReturnRequest as UpdateRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseReturnCrudController extends CrudController {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\PurchaseReturn");
        $this->crud->setRoute('retur-pembelian');
        $this->crud->setEntityNameStrings('Retur Pembelian', 'Retur Pembelian');
        $this->crud->removeButton('update');
        $this->crud->redirectEdit = false;

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'ID'
            ],
            [
                'name' => 'description',
                'label' => 'Deskripsi'
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At'
            ],
            [
                'label' => 'Created by',
                'type' => 'model_function',
                'function_name' => 'getEmployeeName'
            ],
            [
                'name' => 'list_product_po',
                'label' => 'Detail PO',
                'relation' => 'purchaseOrder',
                'relation_label' => 'id',
                'type' => 'link_relation',
                'icon' => 'credit-card',
                'link' => 'purchase-order',
                'link_end' => 'product',
                'link_label' => 'Detail PO'
            ],
            [
                'name' => 'list_product',
                'label' => 'List Product',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'retur-pembelian',
                'link_end' => 'product',
                'link_label' => 'Product'
            ]
        ]);
        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'Retur Pembelian ID',
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
                'name' => 'quantity',
                'label' => 'Quantity',
                'type' => 'pivot'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'status_retur'
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
            ]
        ]);
    }

    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        return view('pages.purchase-return.create_purchase_return', $this->data);
    }

	public function store(PurchaseReturnRequest $request)
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
        $purchaseReturn = new PurchaseReturn();
        $purchaseReturn->employee_id = Auth::id();
        $purchaseReturn->purchase_order_id = $request->purchase_order_id;
        $purchaseReturn->description = $request->description;
        $purchaseReturn->save();
        $data = [];
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $s = $request->status[$i]."";
            $data[$p] = ['quantity' => $q, 'status' => $s];
            if($s == '0'){
                DB::table('product_purchase_order')
                    ->where('purchase_order_id', $request->purchase_order_id)
                    ->where('product_id', $p)
                    ->decrement('quantity', $q);
            }
        }
        $purchaseReturn->products()->sync($data);
        event(new PurchaseOrderModified(PurchaseOrder::find($request->purchase_order_id)));

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return $this->crud->route.'/'.$purchaseReturn->getKey().'/edit';
            default:
                return $request->input('redirect_after_save');
        }
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
