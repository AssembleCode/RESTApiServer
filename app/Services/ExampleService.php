<?php

namespace App\Services;

use App\Validators\ExampleValidator;
use App\Repositories\ExampleRepository;
use App\Traits\Service\RestServiceTrait;

class ExampleService
{
    use RestServiceTrait;

    protected $repository;
    private $validator;

    public function __construct(ExampleRepository $repository, ExampleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }
}
