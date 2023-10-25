<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\JwtService;
use App\Models\OAuthAccessToken;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\DataErrorException;
use App\Exceptions\ValidatorException;
use Illuminate\Validation\ValidationException;
use App\Repositories\OAuthAccessTokenRepository;

class AuthController extends Controller
{
    private $sessionUser;

    private $jwtService;

    private $userRepository;

    private $oAuthAccessTokenRepository;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
        $this->userRepository = new UserRepository();
        $this->oAuthAccessTokenRepository = new OAuthAccessTokenRepository();
        $this->setSessionUser();
    }

    public function setSessionUser()
    {
        try {
            // NEED TO CHECK EXPIRIES OF TOKEN
            $userInfo = $this->jwtService->getUserInfoFromToken();
            $userId = isset($userInfo->id) ? $userInfo->id : null;
            if ($userId) {
                $this->sessionUser = $this->userRepository->find($userId);
            }
        } catch (\Exception $e) {
            $this->sessionUser = null;
        }
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
                $userInfo = $this->userRepository->firstWhere("email", $username);
                $this->validateLoginWithEmail($request, $userInfo, $userCredentials);
            } else {
                $userInfo = $this->userRepository->firstWhere("phone", $username);
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

    // START_WORK
    public function logout()
    {
        if ($this->sessionUser) {
            $token = $this->jwtService->getTokenFromRequest();
            $this->oAuthAccessTokenRepository->revokeToken($token);
            $this->sessionUser = null;

            return response()->json([
                'success' => true,
                'message' => 'Logout successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Unable to Logout'
            ]);
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
