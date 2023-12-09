<?php

namespace App\Services;

use App\Validators\ExampleValidator;
use App\Repositories\ExampleRepository;
use App\Traits\Service\RestServiceTrait;

class ExampleService
{
    private $repository;
    private $validator;
    private $partialUpdateFields = ['status'];

    use RestServiceTrait;

    public function __construct(ExampleRepository $repository, ExampleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }
}
