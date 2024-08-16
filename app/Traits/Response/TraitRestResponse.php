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

    protected function parseResponseDataToArray($data)
    {
        $jsonResponse = response()->json($data)->getData();
        return json_decode(json_encode($jsonResponse), true);
    }

    protected function successResourceResponse($data)
    {
        if (!empty($data)) {
            $dataArray = $this->parseResponseDataToArray($data);
            // $resourceData = $this->resource::withApiRelationalData($dataArray);
            $resourceData = $dataArray;
        }

        $response = [
            'code'         => 200,
            'status'     => 'success',
            'data'         => $resourceData
        ];

        return response()->json($response['data'], $response['code']);
    }

    protected function successResourceCollectionResponse($data)
    {
        if (!empty($data)) {
            $dataArray = $this->parseResponseDataToArray($data);
            // $dataArray['results'] = $this->resource::withApiRelationalData($dataArray['results']);
        }

        $response = [
            'code'         => 200,
            'status'     => 'success',
            'data'         => $dataArray
        ];

        return response()->json($response['data'], $response['code']);
    }
}
