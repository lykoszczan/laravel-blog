<?php

namespace App\Http\Controllers;

use App\Events\PasswordForgotten;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetTokenRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class PasswordResetController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $user_repository,
        private readonly UserService $user_service
    ) {
    }

    public function sendResetToken(SendResetTokenRequest $request): JsonResponse
    {
        $user = $this->user_repository->getByEmail($request->email);

        if ($user) {
            event(new PasswordForgotten($user));
        }

        return response()->json([
            'message' => 'If user exists, mail has been sent',
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        if (
            $this->user_service->changeUserPassword(
                $request->only('email', 'password', 'password_confirmation', 'token')
            )) {
            return response()->json([
                'message' => 'Password has been changed',
            ]);
        }

        return response()->json([
            'message' => 'Invalid data ',
        ], 401);
    }
}
