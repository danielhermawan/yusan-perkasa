<?php

namespace App\Http\Controllers;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Events\SalesTransaction;
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\PaymentReceiptRequest;
use App\Models\PaymentReceipt;
use App\Models\SalesOrder;
use Illuminate\Support\Facades\Auth;

class PaymentReceiptCrudController extends CrudController {

	public function setUp() {

        $this->crud->setModel("App\Models\PaymentReceipt");
        $this->crud->setRoute("pembayaran-penjualan");
        $this->crud->setEntityNameStrings('Pembayaran Penjualan', 'Pembayaran Penjualan');
        $this->crud->removeAllButtons();

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'ID'
            ],
            [
                'name' => 'created_at',
                'label' => 'Created At'
            ],
            [
                'name' => 'total_payment',
                'label' => 'Total Payment'
            ],
            [
                'label' => 'Created by',
                'type' => 'model_function',
                'function_name' => 'getEmployeeName'
            ],
            [
                'name' => 'list_product_so',
                'label' => 'Detail SO',
                'relation' => 'salesOrder',
                'relation_label' => 'id',
                'type' => 'link_relation',
                'icon' => 'usd',
                'link' => 'sales-order',
                'link_end' => 'product',
                'link_label' => 'Detail SO'
            ]
        ]);
    }

	public function store(PaymentReceiptRequest $request)
	{$this->crud->hasAccessOrFail('create');

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
        $paymentReceipt = new PaymentReceipt();
        $paymentReceipt->employee_id = Auth::id();
        $paymentReceipt->sales_order_id = $request->sales_order_id;
        $so = SalesOrder::find($request->sales_order_id);
        $totalPayment = 0;
        $products = $so->products()->get();
        foreach ($products as $p){
            $totalPayment += $p->pivot->price * $p->pivot->quantity;
        }
        $paymentReceipt->total_payment = $totalPayment;
        $paymentReceipt->save();

        event(new SalesTransaction($so));

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        return redirect('penerimaan-pembayaran');
	}

}
