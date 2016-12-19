<?php
/**
 * Created by PhpStorm.
 * User: Daniel
 * Date: 12/15/2016
 * Time: 4:36 PM
 */

namespace App\Libs;


class CrudPanel extends \Backpack\CRUD\CrudPanel 
{
    public $crumb;
    public $parentTitleKey;
    public $detailTitleKey;
    private $detailColumn;
    private $detailCreateField;
    private $detailUpdateField;
    private $detailOption;

    /**
     * CrudPanel constructor.
     * @param $crumbBefore
     */
    public function __construct()
    {
        parent::__construct();
        $this->crumb = collect([]);
        $this->detailColumn = collect([]);
        $this->detailOption = collect([]);
        $this->detailCreateField = collect([]);
        $this->detailUpdateField = collect([]);
    }

    public function addCrumb($crumb = [])
    {
        $this->crumb->push($crumb);
    }

    public function emptyFields()
    {
        $this->create_fields = [];
        $this->update_fields = [];
    }

    public function setDetailColumns($key, $columns = [])
    {
        $this->detailColumn->put($key, $columns);
    }

    public function getDetailColumns($key)
    {
        return $this->detailColumn->get($key);
    }

    public function setDetailCreateFields($key, $columns = [])
    {
        $this->detailCreateField->put($key, $columns);
    }

    public function getDetailCreateFields($key)
    {
        return $this->detailCreateField->get($key);
    }

    public function setDetailUpdateFields($key, $columns = [])
    {
        $this->detailUpdateField->put($key, $columns);
    }

    public function getDetailUpdateFields($key)
    {
        return $this->detailUpdateField->get($key);
    }

    public function setDetailOptions($key, $options = [])
    {
        $this->detailOption->put($key, $options);
    }

    public function getDetailOptions($key)
    {
        return $this->detailOption->get($key);
    }
}