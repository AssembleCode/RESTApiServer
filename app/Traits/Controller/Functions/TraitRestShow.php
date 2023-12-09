<?php

namespace App\Traits\Controller\Functions;

use Exception;
use App\Exceptions\ValidatorException;

trait TraitRestShow
{
    public function show($id)
    {
        try {
            $response = $this->service->showData($id);
            $response = isset($this->resource) ? new $this->resource($response) : $response;
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }
}
