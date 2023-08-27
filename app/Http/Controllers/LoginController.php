<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(
        private readonly UserService $user_service
    ) {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if ($user = $this->user_service->handleLogin($request->email, $request->password)) {
            return response()->json([
                'data' => $user->withoutRelations()->toArray(),
            ]);
        }

        return response()->json([
            'message' => 'Invalid data',
        ], 401);
    }

    public function logout(): JsonResponse
    {
        $this->user_service->handleLogout();

        return response()->json(['message' => 'User logged out.']);
    }
}
