<?php

namespace App\Http\Controllers;

use App\Http\Resources\ItemResource;
use App\Services\ItemService;
use App\Traits\Controller\RestControllerTrait;

class ItemController extends Controller
{
    private $service;
    private $resource;

    use RestControllerTrait;

    public function __construct(ItemService $service)
    {
        $this->service = $service;
        $this->resource = ItemResource::class;
    }
}
