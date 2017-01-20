<?php

namespace App\Http\Controllers;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Events\SalesTransaction;
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\DeliverOrderRequest;
use App\Http\Requests\DeliveryOrderUpdateRequest;
use App\Models\DeliveryOrder;
use App\Models\Product;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DeliveryOrderCrudController extends CrudController {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\DeliveryOrder");
        $this->crud->setRoute("delivery-order");
        $this->crud->setEntityNameStrings('Delivery Order', 'Delivery Order');
        $this->crud->redirectEdit = false;
        $this->crud->removeButton('delete');

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'ID'
            ],
            [
                'name' => 'sending_date',
                'label' => 'Tanggal Pengiriman'
            ],
            [
                'label' => 'Created by',
                'type' => 'model_function',
                'function_name' => 'getEmployeeName'
            ],
            [
                'label' => 'Status',
                'type' => 'status_do',
                'name' => 'status'
            ],
            [
                'name' => 'list_product_so',
                'label' => 'Detail SO',
                'relation' => 'salesOrder',
                'relation_label' => 'id',
                'type' => 'link_relation',
                'icon' => 'credit-card',
                'link' => 'sales-order',
                'link_end' => 'product',
                'link_label' => 'Detail SO'
            ],
            [
                'name' => 'list_product',
                'label' => 'List Product',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'delivery-order',
                'link_end' => 'product',
                'link_label' => 'Product'
            ]
        ]);

        $this->crud->addFields([
            [
                'label' => "Status",
                'type' => 'select_from_array',
                'name' => 'status',
                'options' => ['0' => 'Belum Terkirim', '1' => 'Terkirim'],
                'allows_null' => false,
            ],
        ]);

        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'Delivery Order ID',
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
                'label' => 'Quantity dikirim',
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
            ]
        ]);
    }

    public function getDo()
    {
        return DeliveryOrder::where('status', '1')->get();
    }

    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        return view('pages.delivery-order.create_delivery_order', $this->data);
    }

    public function edit($id)
    {
        $this->crud->hasAccessOrFail('update');

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['fields'] = $this->crud->getUpdateFields($id);
        $this->data['title'] = trans('backpack::crud.edit').' '.$this->crud->entity_name;

        $this->data['id'] = $id;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::edit', $this->data);
    }

    public function store(DeliverOrderRequest $request)
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
        $do = new DeliveryOrder();
        $do->employee_id = Auth::id();
        $do->sales_order_id = $request->sales_order_id;
        $do->sending_date = Carbon::createFromFormat('d-m-Y', $request->sending_date)->toDateString();
        $do->status = '0';
        $do->save();
        $data = [];
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $data[$p] = ['quantity' => $q];
            Product::find($p)->decrement('quantity', $q);
        }
        $do->products()->sync($data);
        // show a success message
        event(new SalesTransaction(SalesOrder::find($request->sales_order_id)));
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return $this->crud->route.'/'.$do->getKey().'/edit';
            default:
                return $request->input('redirect_after_save');
        }
    }

	public function update(DeliveryOrderUpdateRequest $request)
	{
        $do = DeliveryOrder::find($request->id);
        $do->status = $request->status;
        $do->save();
        event(new SalesTransaction(SalesOrder::find($do->salesOrder->id)));
        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return \Redirect::to($this->crud->route);
	}
}
