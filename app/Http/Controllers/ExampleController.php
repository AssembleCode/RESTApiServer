<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExampleResource;
use App\Services\ExampleService;
use App\Traits\Controller\RestControllerTrait;

class ExampleController extends Controller
{
    private $service;
    private $resource;

    use RestControllerTrait;

    public function __construct(ExampleService $service)
    {
        $this->service = $service;
        $this->resource = ExampleResource::class;
    }
}
