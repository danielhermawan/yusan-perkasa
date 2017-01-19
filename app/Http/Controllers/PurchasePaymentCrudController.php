<?php

namespace App\Http\Controllers;

use App\Events\PurchaseOrderModified;
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\PaymentReceiptRequest as StoreRequest;
use App\Http\Requests\PaymentReceiptRequest as UpdateRequest;
use App\Http\Requests\PurchasePaymentRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\Auth;

// VALIDATION: change the requests to match your own file names if you need form validation

class PurchasePaymentCrudController extends CrudController {

	public function setUp() {

        $this->crud->setModel("App\Models\PurchasePayment");
        $this->crud->setRoute("pembayaran-pembelian");
        $this->crud->setEntityNameStrings('Pembayaran Pembelian', 'Pembayaran Pembelian');
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
                'name' => 'list_product_po',
                'label' => 'Detail PO',
                'relation' => 'purchaseOrder',
                'relation_label' => 'id',
                'type' => 'link_relation',
                'icon' => 'credit-card',
                'link' => 'purchase-order',
                'link_end' => 'product',
                'link_label' => 'Detail PO'
            ]
        ]);


    }

	public function store(PurchasePaymentRequest $request)
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
        $purchasePayment = new PurchasePayment();
        $purchasePayment->employee_id = Auth::id();
        $purchasePayment->purchase_order_id = $request->purchase_order_id;
        $po = PurchaseOrder::find($request->purchase_order_id);
        $totalPayment = 0;
        $products = $po->products()->get();
        foreach ($products as $p){
            $totalPayment += $p->pivot->price * $p->pivot->quantity;
        }
        $purchasePayment->total_payment = $totalPayment;
        $purchasePayment->save();

        event(new PurchaseOrderModified($po));

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        return redirect('pembayaran-pembelian');
	}

}
