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
            $response = $this->service->updateData($request, $id);
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }
}
