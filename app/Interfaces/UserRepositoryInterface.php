<?php

namespace App\Interfaces;

use App\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function create(array $details): User;

    public function setUserRoles(int $user_id, array $roles): void;

    public function getByEmail(string $email): ?User;
}
