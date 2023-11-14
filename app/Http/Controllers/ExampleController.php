<?php

namespace App\Http\Controllers;

use App\Exceptions\ValidatorException;
use App\Repositories\ExampleRepository;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    private $repository;

    public function __construct(ExampleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
    }

    public function store(Request $request)
    {
        try {
            if (!isset($this->repository)) {
                $errorMessage = 'Repository not found';
                $response = [
                    'code'    => 422,
                    'status'  => 'error',
                    'data'    => $errorMessage,
                    'message' => 'Unprocessable Entity'
                ];
                return response()->json($response['data']);
            }

            $this->validate(
                $request,
                [
                    'title' => 'required'
                ],
                [
                    'title' => 'Title is required',
                ],
                [
                    'title' => 'Title'
                ]
            ); // REQUEST, RULES, MESSAGES, ATTRIBUTES

            $this->repository->create($request->all());
            $response = [
                'code'    => 200,
                'status'  => 'success',
                'data'    => 'Example created successfully',
                'message' => 'Unprocessable Entity'
            ];
            return response()->json($response['data']);
        } catch (ValidationException $e) {
            throw new ValidatorException($e);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
