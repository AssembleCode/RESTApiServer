<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Repositories\ExampleRepository;
use App\Traits\Controller\RestControllerTrait;
use App\Validators\ExampleValidator;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    private $repository;

    private $validator;

    use RestControllerTrait;

    public function __construct(ExampleRepository $repository, ExampleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function index()
    {
    }

    public function store(Request $request)
    {
        try {
            if (!isset($this->repository)) {
                return $this->errorResponse('Repository not found');
            }

            // REQUEST, RULES, MESSAGES, ATTRIBUTES
            $this->validate($request, $this->validator->rules(), $this->validator->messages(), $this->validator->attributes());

            $storedData = $this->repository->create($request->all());

            return $this->successResponse($storedData);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
        try {
            if (!isset($this->repository)) {
                return $this->errorResponse('Repository not found');
            }

            // REQUEST, RULES, MESSAGES, ATTRIBUTES
            $this->validate($request, $this->validator->rules(), $this->validator->messages(), $this->validator->attributes());

            $this->repository->update($request->all(), $id);
            $response = $this->repository->findById($id);

            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }

    public function destroy($id)
    {
    }
}
