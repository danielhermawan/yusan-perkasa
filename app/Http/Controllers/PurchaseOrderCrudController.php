<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 1/12/2017
 * Time: 3:13 PM
 */

namespace app\Http\Controllers;


use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\PuchaseOrderDetailCreateRequest;
use App\Http\Requests\PuchaseOrderDetailUpdateRequest;
use App\Http\Requests\PuchaseOrderUpdateRequest;
use App\Http\Requests\PurchaseOrderStoreRequest;
use App\Models\PurchaseDemand;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderCrudController extends CrudController
{
    //todo: format date di server dan client dan timezone and date validation
    //todo: column supplier dikasih link
    //todo: develop feature add product in detail
    //todo: validasi qty tidak melebih purchase demand
    public function setup()
    {
        $this->crud->setModel("App\Models\PurchaseOrder");
        $this->crud->setRoute("purchase-order");
        $this->crud->setEntityNameStrings('Purchase Order', 'Purchase Order');
        $this->crud->removeButton('update');
        $this->crud->redirectEdit = false;

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
                'label' => 'Supplier',
                'type' => 'model_function',
                'function_name' => 'getSupplierName'
            ],
            [
                'name' => 'list_product',
                'label' => 'List Product',
                'type' => 'link',
                'icon' => 'shopping-cart',
                'link' => 'purchase-order',
                'link_end' => 'product',
                'link_label' => 'Product'
            ],
            [
                'name' => 'payment',
                'label' => 'Payment',
                'type' => 'payment_button',
                'url'=> 'pembayaran-pembelian',
                'order_id' => 'purchase_order_id',
            ]
        ]);
        $this->crud->addFields([
            [
                'name' => 'id',
                'label' => 'ID',
                'attributes' => [
                    'disabled' => true
                ],
            ],
            [
                'label' => "Supplier",
                'type' => 'select2',
                'name' => 'supplier_id',
                'entity' => 'supplier',
                'attribute' => 'name',
                'model' => "App\Models\Supplier"
            ],
            [
                'label' => "Purchase Demand",
                'type' => 'select2',
                'name' => 'purchase_demand_id',
                'entity' => 'purchase-demand',
                'attribute' => 'id',
                'model' => "App\Models\PurchaseDemand"
            ],
            [
                'name' => 'due_date',
                'label' => 'Due Date',
                'type' => 'date'
            ]
        ]);
        $this->crud->setDetailOptions('product', [
            'label' => 'product',
            'parent_label' => 'PO ID',
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
                'name' => 'max_quantity',
                'label' => 'Max Quantity'
            ],
            [
                'name' => 'quantity',
                'label' => 'Qty di Gudang'
            ],
            [
                'name' => 'quantity',
                'label' => 'Quantity dibeli',
                'type' => 'pivot'
            ],
            [
                'name' => 'status',
                'label' => 'Status',
                'type' => 'status',
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

    public function getPurchaseDemand()
    {
        return PurchaseDemand::all();
    }

    public function getPo()
    {
        return PurchaseOrder::where('status', '0')->orWhere('status', '1')->get();
    }

    public function getProductPo($id)
    {
        $products = PurchaseOrder::find($id)
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

    public function getProductPoForRetur($id)
    {
        $products = PurchaseOrder::find($id)
            ->products()
            ->get();
        $result = [];
        foreach ($products as $p){
            $status = $p->pivot->status;
            //todo:change in return
            if($status == '3' || $status == '4' || $status == '1' || $status == '5')
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

        return view('pages.purchase-order.create_puchase_order', $this->data);
    }

    public function store(PurchaseOrderStoreRequest $request)
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
        $po = new PurchaseOrder;
        $po->employee_id = Auth::id();
        $po->supplier_id = $request->supplier_id;
        $po->purchase_demand_id = $request->purchase_demand_id;
        $po->status = '0';
        $po->due_date = Carbon::createFromFormat('d-m-Y', $request->due_date)->toDateString();
        $po->save();
        $data = [];
        for($i=0; $i < count($request->product); $i++){
            $p = $request->product[$i];
            $q = $request->quantity[$i];
            $price = Supplier::find($request->supplier_id)
                ->products()
                ->where('product_id', $request->product[$i])
                ->first()
                ->pivot
                ->price;
            $data[$p] = ['quantity' => $q, 'price' => $price, 'status' => '1'];
        }
        $po->products()->sync($data);
        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return $this->crud->route.'/'.$po->getKey().'/edit';
            default:
                return $request->input('redirect_after_save');
        }
    }

    public function storeDetail(PuchaseOrderDetailCreateRequest $request, $id)
    {
        return parent::storeDetailCrud(null, $id);
    }

    public function updateDetail(PuchaseOrderDetailUpdateRequest $request, $parent_id, $detail_id)
    {
        return parent::updateDetailCrud(null, $parent_id, $detail_id);
    }

}