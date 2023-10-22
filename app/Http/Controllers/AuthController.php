<?php

namespace App\Http\Controllers;

use App\Exceptions\DataErrorException;
use Exception;
use Illuminate\Http\Request;
use App\Exceptions\ValidatorException;
use App\Models\User;
use App\Services\JwtService;
use Illuminate\Support\Facades\Hash;
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
                $userInfo = User::where("email", $username)->first();
                $this->validateLoginWithEmail($request, $userInfo, $userCredentials);
            } else {
                $userInfo = User::where("phone", $username)->first();
                $this->validateLoginWithPhone($request, $userInfo, $userCredentials);
            }

            // GENERATE JWT token
            $tokenArray = $this->jwtService->generateTokens($userInfo);

            return response()->json([
                'access_token'  => $tokenArray['access_token'],
                'refresh_token' => $tokenArray['refresh_token']
            ]);
        } catch (ValidationException $exception) {
            throw new ValidatorException($exception);
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function validateLoginWithEmail(Request $request, $userInfo, $userCredentials)
    {
        $request->validate(
            [
                'username' => 'required|email|max:255',
                'password' => 'required|min:6',
            ],
            [
                'username.max' => 'The Email may not be greater than 255 characters!',
            ]
        );

        if (empty($userInfo)) {
            throw new DataErrorException(['username' => 'Unrecognized username!']);
        }

        if ($userInfo->status == 0) {
            throw new DataErrorException(['Your account is Deactive!']);
        }

        if (!empty($userCredentials)) {
            if (!Hash::check($userCredentials['password'], $userInfo->password)) {
                throw new DataErrorException(['password' => 'Invalid password!']);
            }
        }

        return true;
    }

    public function validateLoginWithPhone(Request $request, $userInfo, $userCredentials)
    {
        $request->validate(
            [
                'username' => 'required|phone|max:255',
                'password' => 'required|min:6',
            ],
            [
                'username.max' => 'The Phone may not be greater than 255 characters!',
            ]
        );

        if (empty($userInfo)) {
            throw new DataErrorException(['username' => 'Unrecognized username!']);
        }

        if ($userInfo->status == 0) {
            throw new DataErrorException(['Your account is Deactive!']);
        }

        if (!empty($userCredentials)) {
            if (!Hash::check($userCredentials['password'], $userInfo->password)) {
                throw new DataErrorException(['password' => 'Invalid password!']);
            }
        }

        return true;
    }
}
