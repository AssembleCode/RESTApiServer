<?php

namespace App\Traits\Controller\Functions;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\ValidatorException;

trait TraitRestUpdate
{
    public function update(Request $request, $id)
    {
        try {
            $result = $this->service->updateData($request, $id);
            $response = isset($this->resource) ? new $this->resource($result) : $result;
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }
}
