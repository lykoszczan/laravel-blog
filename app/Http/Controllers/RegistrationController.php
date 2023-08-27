<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRegisterRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class RegistrationController extends Controller
{
    public function __construct(private readonly UserService $user_service)
    {
    }

    public function store(StoreRegisterRequest $request): JsonResponse
    {
        $user = $this->user_service->store($request->toArray());

        return response()->json([
            'data' => $user->withoutRelations()->toArray(),
        ], 201);
    }
}
