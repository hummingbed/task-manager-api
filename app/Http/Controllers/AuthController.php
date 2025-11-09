<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use App\Traits\HttpResponses;

class AuthController extends Controller
{
    use HttpResponses;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function signup(SignupRequest $request)
    {
        $result = $this->authService->signup($request->validated());

        return $this->successHttpMessage(
            $result,
            'User registered successfully.',
            201
        );
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());

        return $this->successHttpMessage(
            $result,
            'User logged in successfully.',
            200
        );
    }
}