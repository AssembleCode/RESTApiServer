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
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }
}
