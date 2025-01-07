<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Exception;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    // Register
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = $this->authService->register($data);

            return $this->successResponse([
                'user' => new UserResource($result['user']),
                'token' => $result['token']
            ], 'User registered successfully', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Login
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->validated();
            $token = $this->authService->login($credentials);

            // Menggunakan successResponse untuk format respon yang lebih baik
            return $this->successResponse([
                'token' => $token
            ], 'Login successful');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 401);
        }
    }

    // Get User Info
    public function me(): JsonResponse
    {
        try {
            $user = $this->authService->getUserData();
            return $this->successResponse(new UserResource($user), 'User data retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    // Logout
    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();
            return $this->successResponse([], 'Logged out successfully');
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
