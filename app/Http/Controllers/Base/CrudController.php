<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/9/2016
 * Time: 3:17 PM
 */

namespace App\Http\Controllers\Base;

use App\Libs\CrudPanel;
use Backpack\CRUD\app\Http\Requests\CrudRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as StoreRequest;
use Backpack\CRUD\app\Http\Requests\CrudRequest as UpdateRequest;
use Illuminate\Support\Facades\Request;

class CrudController extends \Backpack\CRUD\app\Http\Controllers\CrudController
{
    protected $exeption = ['redirect_after_save', '_token'];

    /**
     * CrudController constructor.
     * @param array $exeption
     */
    public function __construct()
    {
        $this->crud = new CrudPanel();

        $this->middleware(function ($request, $next) {
            $this->request = $request;
            $this->crud->request = $request;
            $this->setup();

            return $next($request);
        });
    }

    public function addException($exeption = [])
    {
        $this->exeption = array_merge ($this->exeption, $exeption);
    }

    public function getUri($position = -1)
    {
        $uri = Request::path();
        if($position !== -1) {
            return explode('/',$uri)[$position];
        }
        else {
            return $uri;
        }
    }

    public function getParentName()
    {
        return $this->getUri(0);
    }

    public function getDetailName()
    {
        return $this->getUri(2);
    }

    public function storeCrud(StoreRequest $request = null)
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

        // insert item in the db
        $item = $this->crud->create($request->except($this->exeption));

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return \Redirect::to($this->crud->route.'/'.$item->getKey().'/edit');

            default:
                return \Redirect::to($request->input('redirect_after_save'));
        }
    }

    public function updateCrud(UpdateRequest $request = null)
    {
        $this->crud->hasAccessOrFail('update');

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

        // update the row in the db
        $this->crud->update($request->get($this->crud->model->getKeyName()),
            $request->except($this->exeption));

        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return \Redirect::to($this->crud->route);
    }

    public function listDetail($id)
    {
        $this->crud->hasAccessOrFail('list');

        $parentKey = $this->getParentName();
        $detailKey = $this->getDetailName();
        $options = $this->crud->getDetailOptions($detailKey);

        $model = $this->crud->getEntry($id);

        $this->crud->addCrumb(['name' => $this->crud->entity_name_plural, 'link' => $this->crud->getRoute()]);
        $this->crud->setRoute($this->crud->getRoute()."/".$model->getKey().'/'. $detailKey);
        $this->crud->setEntityNameStrings( $detailKey.' '.$parentKey.' '.$model->{$options['parentTitleKey']},
            $detailKey.' '.$parentKey.' '.$model->{$options['parentTitleKey']});

        $this->crud->setColumns($this->crud->getDetailColumns($detailKey));

        $this->data['crud'] = $this->crud;
        $this->data['title'] = ucfirst($this->crud->entity_name_plural);

        // get all entries if AJAX is not enabled
        if (! $this->data['crud']->ajaxTable()) {
            $relation = $options['relation'];
            $this->data['entries'] = $model->$relation()->get();
        }

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        // $this->crud->getListView() returns 'list' by default, or 'list_ajax' if ajax was enabled
        return view('crud::list', $this->data);
    }

    public function createDetail($id)
    {
        $this->crud->hasAccessOrFail('create');

        $parentKey = $this->getParentName();
        $detailKey = $this->getDetailName();
        $options = $this->crud->getDetailOptions($detailKey);

        $model = $this->crud->getEntry($id);

        $this->crud->addCrumb(['name' => $this->crud->entity_name_plural, 'link' => $this->crud->getRoute()]);
        $this->crud->setRoute($this->crud->getRoute()."/".$model->getKey().'/'. $detailKey);
        $this->crud->setEntityNameStrings( $detailKey.' '.$parentKey.' '.$model->{$options['parentTitleKey']},
            $detailKey.' '.$parentKey.' '.$model->{$options['parentTitleKey']});

        $this->data['crud'] = $this->crud;
        $this->data['fields'] = $this->crud->getDetailCreateFields($detailKey);
        $this->data['title'] = trans('backpack::crud.add').' '.$this->crud->entity_name;

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::create', $this->data);
    }

    public function storeDetailCrud(CrudRequest $request = null, $id)
    {
        $this->crud->hasAccessOrFail('create');

        $parentKey = $this->getParentName();
        $detailKey = $this->getDetailName();
        $options = $this->crud->getDetailOptions($detailKey);

        $model = $this->crud->getEntry($id);

        $this->crud->addCrumb(['name' => $this->crud->entity_name_plural, 'link' => $this->crud->getRoute()]);
        $this->crud->setRoute($this->crud->getRoute()."/".$model->getKey().'/'. $detailKey);
        $this->crud->setEntityNameStrings( $detailKey.' '.$parentKey.' '.$model->{$options['parentTitleKey']},
            $detailKey.' '.$parentKey.' '.$model->{$options['parentTitleKey']});

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

        $model = $this->crud->getEntry($id);
        $relation = $options['relation'];

        $fields = collect($this->crud->getDetailCreateFields($detailKey));
        $hasSelect2 = false;

        foreach ($fields as $f){
            if($f['type'] == 'select2_multiple'){
                foreach (collect($request->input($f['name'])) as $p){
                    $d = [];
                    foreach ($request->input() as $key => $value){
                        if($key != $f['name'] && $key != '_token' && $key != 'redirect_after_save')
                            $d = [$key => $value];
                    }
                    $data[$p] = $d;
                }
                $model->$relation()->syncWithoutDetaching($data);
                $hasSelect2 = true;
            }
        }

        if(!$hasSelect2){
            $this->addException([$options['detail_field']]);
            $model->$relation()->syncWithoutDetaching([$request->{$options['detail_field']} => $request->except($this->exeption)]);
        }

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // redirect the user where he chose to be redirected
        switch ($request->input('redirect_after_save')) {
            case 'current_item_edit':
                return \Redirect::to($this->crud->route.'/'.$model->getKey().'/edit');

            default:
                return \Redirect::to($request->input('redirect_after_save'));
        }
    }

    public function editDetail($parent_id, $detail_Id)
    {
        $this->crud->hasAccessOrFail('update');

        $parentKey = $this->getParentName();
        $detailKey = $this->getDetailName();
        $options = $this->crud->getDetailOptions($detailKey);
        $relation = $options['relation'];

        $parentModel = $this->crud->getEntry($parent_id);
        $detailModel = $parentModel->$relation()->where($options['detail_key'], $detail_Id)->first();

        $this->crud->addCrumb(['name' => $this->crud->entity_name_plural, 'link' => $this->crud->getRoute()]);
        $this->crud->setRoute($this->crud->getRoute()."/".$parentModel->getKey().'/'. $detailKey);
        $this->crud->setEntityNameStrings( $detailKey.' '.$parentKey.' '.$parentModel->{$options['parentTitleKey']},
            $detailKey.' '.$parentKey.' '.$parentModel->{$options['parentTitleKey']});

        $this->data['entry'] = $detailModel;
        $this->data['crud'] = $this->crud;

        $this->data['title'] = $detailKey.' '.$parentKey.' '.$parentModel->{$options['parentTitleKey']};
        $this->data['id'] = $detailModel->id;

        $fields = [];
        foreach ($this->crud->getDetailUpdateFields($detailKey) as $f){
            if($f['from'] == 'pivot')
                $f['default'] = $detailModel->pivot->{$f['name']};
            else if($f['from'] == 'detail')
                $f['default'] = $detailModel->{$f['name']};
            else if($f['from'] == 'parent')
                $f['default'] = $parentModel->{$f['name']};
            $fields[] = $f;
        }
        $this->data['fields'] =  $fields;


        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view('crud::edit', $this->data);
    }

    public function updateDetailCrud(CrudRequest $request = null, $parent_id, $detail_id)
    {
        $this->crud->hasAccessOrFail('update');

        $parentKey = $this->getParentName();
        $detailKey = $this->getDetailName();
        $options = $this->crud->getDetailOptions($detailKey);
        $relation = $options['relation'];

        $parentModel = $this->crud->getEntry($parent_id);

        $this->crud->addCrumb(['name' => $this->crud->entity_name_plural, 'link' => $this->crud->getRoute()]);
        $this->crud->setRoute($this->crud->getRoute()."/".$parentModel->getKey().'/'. $detailKey);
        $this->crud->setEntityNameStrings( $detailKey.' '.$parentKey.' '.$parentModel->{$options['parentTitleKey']},
            $detailKey.' '.$parentKey.' '.$parentModel->{$options['parentTitleKey']});

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

        $parentModel->$relation()->updateExistingPivot($detail_id, $request->except('redirect_after_save', '_token', '_method'));


        // show a success message
        \Alert::success(trans('backpack::crud.update_success'))->flash();

        return \Redirect::to($this->crud->route);
    }

    public function destroyDetail($parent_id, $detail_Id)
    {
        $this->crud->hasAccessOrFail('delete');

        $detailKey = $this->getDetailName();
        $options = $this->crud->getDetailOptions($detailKey);

        $model = $this->crud->getEntry($parent_id);
        $relation = $options['relation'];

        return $model->$relation()->detach($detail_Id);
    }
}