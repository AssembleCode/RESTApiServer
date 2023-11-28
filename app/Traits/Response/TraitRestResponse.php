<?php

namespace App\Traits\Response;

trait TraitRestResponse
{
    protected function successResponse($data)
    {
        $response = [
            'code'   => 200,
            'status' => 'Success',
            'data'   => $data,
        ];
        return response()->json($response['data'], $response['code']);
    }

    protected function errorResponse($data = null)
    {
        $response = [
            'code'    => 422,
            'status'  => 'Error',
            'data'    => $data,
            'message' => 'Unprocessable Entity',
        ];
        return response()->json($response['data'], $response['code']);
    }

    protected function notFoundResponse($data = 'Item Not Found')
    {
        $response = [
            'code'   => 404,
            'status' => 'error',
            'data'   => $data,
        ];
        return response()->json($response['data'], $response['code']);
    }

    protected function deleteResponse()
    {
        $response = [
            'code'    => 204,
            'status'  => 'success',
            'data'    => [],
            'message' => 'Data Delete Successfully !'
        ];
        return response()->json($response['message'], $response['code']);
    }
}
