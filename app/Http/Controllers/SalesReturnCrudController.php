<?php

namespace App\Http\Controllers;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Events\SalesTransaction;
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\SalesReturnRequest;
use App\Http\Requests\SalesReturnRequest as UpdateRequest;
use App\Models\DeliveryOrder;
use App\Models\SalesOrder;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesReturnCrudController extends CrudController {

	public function setUp() {

        $this->crud->setModel("App\Models\SalesReturn");
        $this->crud->setRoute('retur-penjualan');
        $this->crud->setEntityNameStrings('Retur Penjualan', 'Retur Penjualan');
        $this->crud->removeButton('update');
        $this->crud->removeButton('delete');
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
                'name' => 'list_product_do',
                'label' => 'Detail DO',
                'relation' => 'deliveryOrder',
                'relation_label' => 'id',
                'type' => 'link_relation',
                'icon' => 'send',
                'link' => 'delivery-order',
                'link_end' => 'product',
                'link_label' => 'Detail DO'
            ],
            [
                'name' => 'list_product',
                'label' => 'List Product',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'retur-penjualan',
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

        return view('pages.sales-return.create_sales_return', $this->data);
    }

    public function store(SalesReturnRequest $request)
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
        $salesReturn = new SalesReturn();
        $salesReturn->employee_id = Auth::id();
        $salesReturn->delivery_order_id = $request->delivery_order_id;
        $salesReturn->description = $request->description;
        $salesReturn->save();
        $data = [];
        $so = DeliveryOrder::find( $request->delivery_order_id)->salesOrder;
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $s = $request->status[$i]."";
            $data[$p] = ['quantity' => $q, 'status' => $s];
            if($s == '0'){
                DB::table('product_sales_order')
                    ->where('sales_order_id', $so->id)
                    ->where('product_id', $p)
                    ->decrement('quantity', $q);
            }
            DB::table('delivery_order_product')
                ->where('delivery_order_id', $request->delivery_order_id)
                ->where('product_id', $p)
                ->decrement('quantity', $q);
        }
        $salesReturn->products()->sync($data);
        event(new SalesTransaction(SalesOrder::find($so->id)));

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return $this->crud->route.'/'.$salesReturn->getKey().'/edit';
            default:
                return $request->input('redirect_after_save');
        }
    }


	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
