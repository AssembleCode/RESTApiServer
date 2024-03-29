<?php

namespace App\Traits\Controller\Functions;

use Exception;
use App\Exceptions\ValidatorException;

trait TraitRestIndex
{
    public function index()
    {
        try {
            $response = $this->service->index();
            $response = isset($this->resource) ? $this->resource::collection($response) : $response;
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }
}
