<?php

namespace App\Http\Controllers;

use App\Services\ExampleService;
use App\Traits\Controller\RestControllerTrait;

class ExampleController extends Controller
{
    use RestControllerTrait;

    private $service;
    private $validator;

    public function __construct(ExampleService $service)
    {
        $this->service = $service;
    }
}
