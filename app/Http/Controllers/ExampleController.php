<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Http\Requests\ExampleRequest;
use App\Repositories\ExampleRepository;
use App\Services\ExampleService;
use App\Traits\Controller\RestControllerTrait;
use App\Validators\ExampleValidator;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    use RestControllerTrait;

    private $service;
    private $validator;

    public function __construct(ExampleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $response = $this->service->index();
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }

    // public function store(ExampleRequest $request)
    public function store(Request $request)
    {
        try {
            // USING FORM REQUEST CLASS
            // $response = $this->service->storeData($request->validationData());
            // return $this->successResponse($response);

            // USING VALIDATION CLASS
            $response = $this->service->storeData($request);
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }

    public function show($id)
    {
        try {
            $response = $this->service->showData($id);
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $response = $this->service->updateData($request, $id);
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }

    public function destroy($id)
    {
    }
}
