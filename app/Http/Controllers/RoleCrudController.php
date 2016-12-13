<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/8/2016
 * Time: 5:25 PM
 */

namespace app\Http\Controllers;

use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\RoleRequest;

class RoleCrudController extends CrudController
{
    public function setup()
    {
        $this->crud->setModel("App\Models\Role");
        $this->crud->setRoute("role");
        $this->crud->setEntityNameStrings('role', 'roles');

        $this->crud->setColumns(['name']);
        $this->crud->addField([
            'name' => 'name',
            'label' => "Role name"
        ]);
    }

    public function store(RoleRequest $request)
    {
        return parent::storeCrud();
    }

    public function update(RoleRequest $request)
    {
        return parent::updateCrud();
    }
}