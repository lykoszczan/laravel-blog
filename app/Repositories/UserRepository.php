<?php

namespace App\Repositories;

use App\Constants\Roles;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class UserRepository implements UserRepositoryInterface
{

    public function getAll(): LengthAwarePaginator
    {
        return User::with('roles')->paginate();
    }

    public function remove(int $id): void
    {
        $user = User::where('id', $id);
        $user->delete();
    }

    public function edit(int $id, array $details): void
    {
        $user = User::where('id', $id);
        $user->update($details);
    }

    public function create(array $details): User
    {
        $user = User::create($details);
        $user->generateToken();
        $user->assignRole(Roles::USER);

        return $user;
    }

    public function setUserRoles(int $user_id, array $roles): void
    {
        $user = User::where('id', $user_id)->first();
        $rolesModels = Role::findOrFail($roles);

        $user?->syncRoles(...$rolesModels);
    }

    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
