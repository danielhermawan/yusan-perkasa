<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\CrudController;
use App\Http\Requests\EmployeeStoreRequest as StoreRequest;
use App\Http\Requests\EmployeeUpdateRequest as UpdateRequest;

class EmployeeCrudController extends CrudController
{
	public function setup() {

        $this->crud->setModel("App\Models\Employee");
        $this->crud->setRoute("employee");
        $this->crud->setEntityNameStrings('employee', 'employees');

        $this->crud->setColumns(['name','phone', 'email', 'address', 'gender']);
        $this->addException(['password_confirmation']);
        $this->crud->addColumns([[
            'name' => 'ktp_id',
            'label' => 'ID Ktp'
        ], [
            'name' => 'hire_date',
            'label' => 'Hire Date'
        ], [
            'label' => 'Role',
            'type' => 'model_function',
            'function_name' => 'getRoleName'
        ]]);
        $this->crud->addFields([[
            'name' => 'name',
            'label' => "Nama Karyawan"
        ], [
            'name' => 'phone',
            'label' => 'Nomor Handphone'
        ], [
            'name' => 'ktp_id',
            'label' => 'ID Ktp'
        ], [
            'name' => 'hire_date',
            'label' => 'Hire Date',
            'type' => 'date_picker',
            'date_picker_options' => [
                'todayBtn' => true,
                'format' => 'dd-mm-yyyy',
            ],
        ], [
            'name' => 'address',
            'label' => 'Alamat',
            'type' => 'address'
        ], [
            'name'        => 'gender',
            'label'       => 'Gender',
            'type'        => 'radio',
            'options'     => [
                "pria" => "Pria",
                "wanita" => "Wanita"
            ],
        ], [
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email'
        ], [
            'name' => 'password',
            'label' => 'Password',
            'type' => 'password'
        ],
        [
            'name' => 'password_confirmation',
            'label' => 'Password Confirmation',
            'type' => 'password'
        ], [
            'label' => "Role",
            'type' => 'select2',
            'name' => 'role_id',
            'entity' => 'role',
            'attribute' => 'name',
            'model' => "App\Models\Role"
        ]]);
    }

	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}

	public function update(UpdateRequest $request)
	{
        if($request->input('password','') === '')
            $this->addException(['password']);
		return parent::updateCrud();
	}
}
