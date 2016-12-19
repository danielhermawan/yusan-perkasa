<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\UnitRequest as StoreRequest;
use App\Http\Requests\UnitRequest as UpdateRequest;

class UnitCrudController extends CrudController {

	public function setUp()
    {
        $this->crud->setModel("App\Models\Unit");
        $this->crud->setRoute("unit");
        $this->crud->setEntityNameStrings('unit', 'units');

        $this->crud->setColumns(['name']);
        $this->crud->addField([
            'name' => 'name',
            'label' => "Unit name"
        ]);

    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
}
