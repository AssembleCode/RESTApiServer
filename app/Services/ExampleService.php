<?php

namespace App\Services;

use App\Exceptions\ValidatorException;
use Illuminate\Http\Request;
use App\Validators\ExampleValidator;
use App\Repositories\ExampleRepository;
use App\Traits\Controller\RestControllerTrait;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class ExampleService
{
    use RestControllerTrait;

    protected $repository;
    private $validator;


    public function __construct(ExampleRepository $repository, ExampleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function index()
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        $response = $this->repository->all();
        return $response;
    }

    public function storeData($request)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        // REQUEST, RULES, MESSAGES, ATTRIBUTES
        // Validator::make($request->all(), $this->validator->rules(), $this->validator->messages(), $this->validator->attributes())->validate();
        $request->validate($this->validator->rules(), $this->validator->messages(), $this->validator->attributes());
        $storedData = $this->repository->create($request->all());
        return $storedData;
    }

    public function updateData($request, $id)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        // REQUEST, RULES, MESSAGES, ATTRIBUTES
        $request->validate($this->validator->rules(), $this->validator->messages(), $this->validator->attributes());
        $this->repository->update($request->all(), $id);
        $response = $this->repository->findById($id);
        return $response;
    }

    public function showData($id)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        $response = $this->repository->findById($id);
        return $response;
    }
}
