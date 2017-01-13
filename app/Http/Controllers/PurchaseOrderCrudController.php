<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 1/12/2017
 * Time: 3:13 PM
 */

namespace app\Http\Controllers;


use App\Http\Controllers\Base\CrudController;

class PurchaseOrderCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\PurchaseOrder");
        $this->crud->setRoute("purchase-order");
        $this->crud->setEntityNameStrings('Purchase Order', 'Purchase Order');

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
}