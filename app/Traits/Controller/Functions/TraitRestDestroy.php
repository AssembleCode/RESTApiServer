<?php

namespace App\Traits\Controller\Functions;

use Exception;
use App\Exceptions\ValidatorException;

trait TraitRestDestroy
{
    public function destroy($id)
    {
        try {
            $response = $this->service->deleteData($id);
            return $this->successResponse($response);
        } catch (Exception $exception) {
            throw new ValidatorException($exception);
        }
    }
}
