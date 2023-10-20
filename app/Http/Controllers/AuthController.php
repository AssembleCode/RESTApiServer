<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\ValidatorException;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function login(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'required|max:255',
                    'password' => 'required|min:6',
                ],
                [
                    'username' => 'The Phone or Email field is required',
                ]
            );

            $userCredentials = $request->only('username', 'password');
            $username = $userCredentials['username'];

            if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                $field = 'email';
            } else {
                $field = 'phone';
            }

            $userInfo = User::where("$field", $username)->first();

            // GENERATE JWT token
            $tokens = $this->jwtService->generateTokens($userInfo);

            return $tokens;

            return response()->json([
                'access_token' => 'The access token',
                'refresh_token' => 'The fresh token',
            ]);
        } catch (ValidationException $exception) {
            throw new ValidatorException($exception);
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
