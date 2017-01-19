<?php

namespace App\Http\Controllers;


// VALIDATION: change the requests to match your own file names if you need form validation
use App\Events\PurchaseOrderModified;
use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\PaymentReceiptRequest as StoreRequest;
use App\Http\Requests\PaymentReceiptRequest as UpdateRequest;
use App\Http\Requests\ProductReceiptRequest;
use App\Models\Product;
use App\Models\ProductReceipt;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ProductReceiptCrudController extends CrudController  {

	public function setUp() {

        $this->crud->setModel("App\Models\ProductReceipt");
        $this->crud->setRoute("penerimaan-barang");
        $this->crud->setEntityNameStrings('Penerimaan Barang', 'Penerimaan Barang');
        $this->crud->removeButton('update');
        $this->crud->redirectEdit = false;

        $this->crud->addColumns([
            [
                'name' => 'id',
                'label' => 'ID'
            ],
            [
                'name' => 'receiving_date',
                'label' => 'Tanggal Penerimaan'
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
                'link' => 'penerimaan-barang',
                'link_end' => 'product',
                'link_label' => 'Product'
            ]
        ]);
        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'Penerimaan Barang ID',
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
                'label' => 'Quantity di Terima',
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

    public function create()
    {
        $this->crud->hasAccessOrFail('create');
        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        return view('pages.product-receipt.create_product_receipt', $this->data);
    }

    public function store(ProductReceiptRequest $request)
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
        $productReceipt = new ProductReceipt;
        $productReceipt->employee_id = Auth::id();
        $productReceipt->purchase_order_id = $request->purchase_order_id;
        $productReceipt->receiving_date = Carbon::createFromFormat('d-m-Y', $request->receiving_date)->toDateString();
        $productReceipt->save();
        $data = [];
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $data[$p] = ['quantity' => $q];

            Product::find($p)->increment('quantity', $q);
        }
        $productReceipt->products()->sync($data);
        event(new PurchaseOrderModified(PurchaseOrder::find($request->purchase_order_id)));

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return $this->crud->route.'/'.$productReceipt->getKey().'/edit';
            default:
                return $request->input('redirect_after_save');
        }
    }

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
