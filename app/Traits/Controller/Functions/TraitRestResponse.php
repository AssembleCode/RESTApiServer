<?php

namespace App\Traits\Controller\Functions;

trait TraitRestResponse
{
    protected function successResponse($data)
    {
        $response = [
            'code'    => 200,
            'message' => 'Success',
            'data'    => $data,
        ];
        return response()->json($response['data'], $response['code']);
    }

    protected function errorResponse($data)
    {
        $response = [
            'code'    => 422,
            'message' => 'Error',
            'data'    => $data,
        ];
        return response()->json($response['data'], $response['code']);
    }
}
