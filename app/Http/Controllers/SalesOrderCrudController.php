<?php

namespace App\Http\Controllers;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\SalesOrderRequest;
use App\Http\Requests\SalesOrderRequest as UpdateRequest;
use App\Models\Customer;
use App\Models\DeliveryOrder;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesOrderCrudController extends CrudController {

	public function setUp() {

        /*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
        $this->crud->setModel("App\Models\SalesOrder");
        $this->crud->setRoute("sales-order");
        $this->crud->setEntityNameStrings('Saler Order', 'Sales Order');
        $this->crud->removeButton('delete');

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'ID'
            ],
            [
                'name' => 'due_date',
                'label' => 'Tanggal Jatuh Tempo'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'status_order'
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
                'label' => 'Customer',
                'type' => 'model_function',
                'function_name' => 'getCustomerName'
            ],
            [
                'name' => 'list_product',
                'label' => 'List Product',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'sales-order',
                'link_end' => 'product',
                'link_label' => 'Product'
            ],
            [
                'name' => 'payment',
                'order_id' => 'sales_order_id',
                'label' => 'Payment',
                'type' => 'payment_button',
                'url' => 'penerimaan-pembayaran'
            ]
        ]);

        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'SO ID',
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
                'name' => 'min_quantity',
                'label' => 'Min Quantity'
            ],
            [
                'name' => 'quantity',
                'label' => 'Qty di Gudang'
            ],
            [
                'name' => 'quantity',
                'label' => 'Quantity dijual',
                'type' => 'pivot'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'status_so',
                'po_id' => $this->getUri(1)
            ],
            [
                'name' => 'price',
                'label' => 'Harga',
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

    public function getSo()
    {
        return SalesOrder::where('status', '0')->orWhere('status', '1')->get();
    }

    public function getProductSo($id)
    {
        $products = SalesOrder::find($id)
            ->products()
            ->get();
        $result = [];
        foreach ($products as $p){
            $status = $p->pivot->status;
            if($status == '3' || $status == '4' || $status == '1' || $status == '5')
                $result[] = $p;
        }
        return $result;
    }

    public function getProductDo($id)
    {
        return DeliveryOrder::where('status', '1')->get();
    }

    public function getProductSoForRetur($id)
    {
        $do = DeliveryOrder::find($id);
        $products = $do
            ->products()
            ->get();
        $result = [];
        foreach ($products as $p){
            $status = DB::table('product_sales_order')
                ->where('product_id', $p->id)
                ->where('sales_order_id', $do->salesOrder->id)
                ->first()->status;
            if($status == '3' || $status == '4' || $status == '1' || $status == '5' || $status == '2')
                $result[] = $p;
        }
        return $result;
    }

    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['fields'] = $this->crud->getCreateFields();
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        return view('pages.sales-order.create_sales_order', $this->data);
    }

    public function store(SalesOrderRequest $request)
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
        $so = new SalesOrder();
        $so->employee_id = Auth::id();
        $so->customer_id = $request->customer_id;
        $so->status = '0';
        $so->due_date = Carbon::createFromFormat('d-m-Y', $request->due_date)->toDateString();
        $so->save();
        $data = [];
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $price = Customer::find($request->customer_id)
                ->products()
                ->where('product_id', $request->product[$i])
                ->first()
                ->pivot
                ->price;
            $data[$p] = ['quantity' => $q, 'price' => $price, 'status' => '1'];
        }
        $so->products()->sync($data);
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return $this->crud->route.'/'.$so->getKey().'/edit';
            default:
                return $request->input('redirect_after_save');
        }
    }

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}


}
