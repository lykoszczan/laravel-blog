<?php

namespace App\Services;

use App\Constants\Roles;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(private readonly UserRepositoryInterface $user_repository)
    {
    }

    public function store(array $user_details): User
    {
        $user = $this->user_repository->create([
            'name' => $user_details['name'],
            'email' => $user_details['email'],
            'password' => Hash::make($user_details['password']),
        ]);

        event(new Registered($user));

        return $user;
    }

    public function handleLogin(string $user_email, string $user_password): ?User
    {
        $user = $this->user_repository->getByEmail($user_email);

        if (!$user || !$this->canLogin($user_password, $user)) {
            return null;
        }

        $user->generateToken();

        return $user;
    }

    public function handleLogout(): void
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if ($user) {
            $this->user_repository->edit($user->id, [
                'api_token' => null,
            ]);
        }

    }

    private function canLogin(string $passwordToCheck, User $user): bool
    {
        return $this->validateUserPassword($passwordToCheck, $user->password) && (
                $user->hasRole(Roles::ADMIN) || $user->hasRole(Roles::EDITOR)
            );
    }

    private function validateUserPassword(string $passwordToCheck, string $userPassword): bool
    {
        return Hash::check($passwordToCheck, $userPassword);
    }

    public function changeUserPassword(array $password_reset_details): bool
    {
        $status = Password::reset(
            $password_reset_details,
            function (User $user, string $password) {
                $this->user_repository->edit(
                    $user->id,
                    [
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ]
                );

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET;
    }
}
