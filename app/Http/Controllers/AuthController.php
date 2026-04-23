<?php

namespace App\Http\Controllers;

use App\DTO\Auth\SigninData;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\SigninRequest;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
        //
    }

    /**
     * @unauthenticated
     */
    public function signin(SigninRequest $request)
    {
        $user = $this->authService->signin(SigninData::fromRequest($request));

        return ResponseHelper::success($user);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return ResponseHelper::success();
    }
}
