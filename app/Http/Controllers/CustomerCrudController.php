<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/13/2016
 * Time: 4:49 PM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\CustomerRequest;

class CustomerCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\Customer");
        $this->crud->setRoute("customer");
        $this->crud->setEntityNameStrings('customer', 'customers');

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
                'label' => "Nama Pelanggan"
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

    public function store(CustomerRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(CustomerRequest $request)
    {
        return parent::updateCrud();
    }
}