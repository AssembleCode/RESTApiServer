<?php

namespace App\Traits\Controller\Functions;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\ValidatorException;

trait TraitRestStore
{
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
}
