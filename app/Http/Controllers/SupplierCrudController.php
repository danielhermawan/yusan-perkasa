<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/13/2016
 * Time: 4:05 PM
 */

namespace App\Http\Controllers;


use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\SupplierRequest;

class SupplierCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\Supplier");
        $this->crud->setRoute("supplier");
        $this->crud->setEntityNameStrings('supplier', 'suppliers');

        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => 'Nama'
            ],
            [
                'name' => 'phone',
                'label' => 'Phone'
            ],
            [
                'name' => 'email',
                'label' => 'Email'
            ],
            [
                'name' => 'address',
                'label' => 'Alamat'
            ],
            [
                'name' => 'zip_code',
                'label' => 'Kode Pos'
            ]
        ]);
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => "Nama Supplier"
            ],
            [
                'name' => 'phone',
                'label' => "Phone Number"
            ],
            [
                'name' => 'email',
                'label' => 'Email',
                'type' => 'email'
            ],
            [
                'name' => 'address',
                'label' => 'Alamat',
                'type' => 'address'
            ],
            [
                'name' => 'zip_code',
                'label' => 'Kode Pos'
            ]
        ]);
    }

    public function store(SupplierRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(SupplierRequest $request)
    {
        return parent::updateCrud();
    }
}