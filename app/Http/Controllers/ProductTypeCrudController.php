<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/9/2016
 * Time: 4:51 PM
 */

namespace app\Http\Controllers;

use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\ProductTypeRequest;

class ProductTypeCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\ProductType");
        $this->crud->setRoute("product-type");
        $this->crud->setEntityNameStrings('product type', 'product types');

        $this->crud->setColumns(['name']);
        $this->crud->addField([
            'name' => 'name',
            'label' => "Type name"
        ]);
    }

    public function store(ProductTypeRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(ProductTypeRequest $request)
    {
        return parent::updateCrud();
    }
}