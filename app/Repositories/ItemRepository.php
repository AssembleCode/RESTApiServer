<?php

namespace App\Repositories;

use App\Models\Item;
use App\Services\ODataService;

class ItemRepository extends BaseRepository
{
    /**
     * @var Item
     */
    protected $model;

    protected $request;

    protected $oDataService;

    protected $fieldSearchable = ['name', 'description'];

    public function __construct()
    {
        $this->model = new Item();
    }

    protected function init()
    {
        $this->request = request();
        $this->oDataService = (new ODataService())->init();
    }
}
