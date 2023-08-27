<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditUserRequest;
use App\Http\Requests\RemoveUserRequest;
use App\Http\Requests\SetUserRolesRequest;
use App\Http\Requests\StoreRegisterRequest;
use App\Interfaces\UserRepositoryInterface;
use App\Services\UserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(private readonly UserService $user_service, private readonly UserRepositoryInterface $user_repository)
    {
    }

    public function index(): LengthAwarePaginator
    {
        return $this->user_repository->getAll();
    }

    public function store(StoreRegisterRequest $request): JsonResponse
    {
        $user = $this->user_service->store($request->toArray());

        return response()->json([
            'data' => $user->withoutRelations()->toArray(),
        ], 201);
    }

    public function remove(RemoveUserRequest $request): JsonResponse
    {
        $this->user_repository->remove($request->id);

        return response()->json([
            'message' => 'User removed successfully',
        ]);
    }

    public function edit(EditUserRequest $request): JsonResponse
    {
        $this->user_repository->edit($request->id, [
            'email' => $request->email,
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'User edited successfully',
        ]);
    }

    public function setRoles(SetUserRolesRequest $request): JsonResponse
    {
        $this->user_repository->setUserRoles($request->id, $request->roles);

        return response()->json([
            'message' => 'User roles edited successfully',
        ]);
    }
}
