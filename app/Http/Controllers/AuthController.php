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
use App\Services\PasswordPatternService;
use App\Services\SessionService;

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
            $sessionService = (new SessionService())->init();
            $userInfo = $sessionService->getSessionUserInfo();

            if ($userInfo != null) {
                // VERIFIED TOKEN
                $userId = isset($userInfo->id) ? $userInfo->id : null;
                $this->sessionUser = $this->userRepository->findById($userId);
            } else {
                // EXPIRED TOKEN / REVOKED
                $this->sessionUser = null;
            }
        } catch (\Exception $e) {
            $this->sessionUser = null;
        }
    }

    public function register(Request $request)
    {
        try {
            // ADMIN CAN CHANGE THIS PATTERN FROM PASSWORD PATTERN UI
            $passwordPatternService = new PasswordPatternService(8, true, true, true, true); //VALUE WILL BE DYNAMIC
            $passwordPolicy = $passwordPatternService->policyMaker();
            $passPattern = '/' . $passwordPolicy['pattern'] . '/';
            $passLength = $passwordPolicy['patterns']['length'];
            $message = $passwordPolicy['patterns']['message'];

            $request->validate([
                'name'     => 'required',
                'email'    => 'required|unique:users,email|max:255',
                'phone'    => 'required|unique:users,phone|max:20',
                'password' => ["required", "min:$passLength", "regex:$passPattern"],
            ], [
                'password.regex' => $message,
            ]);

            $this->userRepository->create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => $request->password,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User registration successful',
            ]);
        } catch (ValidationException $exception) {
            throw new ValidatorException($exception);
        } catch (Exception $exception) {
            throw $exception;
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

            //REVOKE ALL PREVIOUS TOKEN
            $this->oAuthAccessTokenRepository->revokePreviousTokens($userInfo->id);

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

    public function logout()
    {
        if ($this->sessionUser) {
            $sessionService = (new SessionService())->init();
            $sessionUserToken = $sessionService->getSessionUserToken();
            $this->oAuthAccessTokenRepository->revokeToken($sessionUserToken);
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
