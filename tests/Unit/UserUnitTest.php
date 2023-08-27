<?php

namespace Tests\Unit;

use App\Constants\Roles;
use App\Models\User;
use App\Repositories\UserRepository;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserUnitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_it_can_create_a_user(): void
    {
        $data = [
            'name' => fake()->name,
            'email' => fake()->email,
            'password' => Hash::make(fake()->password),
        ];

        $user_repository = new UserRepository();
        $user = $user_repository->create($data);

        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['password'], $user->password);
        $this->assertTrue($user->hasRole(Roles::USER));
    }

    public function test_it_can_set_role(): void
    {
        $user = User::factory()->user()->create(['email' => 'user@test.pl']);
        $role_admin = Role::where('name', Roles::ADMIN)->first();
        $role_editor = Role::where('name', Roles::EDITOR)->first();

        $user_repository = new UserRepository();
        $user_repository->setUserRoles($user->id, [
            $role_admin->id,
            $role_editor->id,
        ]);

        $updated_user = User::where('id', $user->id)->first();

        $this->assertTrue($updated_user->hasRole(Roles::ADMIN));
        $this->assertTrue($updated_user->hasRole(Roles::EDITOR));
        $this->assertFalse($updated_user->hasRole(Roles::USER));
    }

    public function test_set_not_existing_role(): void
    {
        $user = User::factory()->user()->create(['email' => 'user@test.pl']);

        $user_repository = new UserRepository();

        $this->expectException(ModelNotFoundException::class);
        $user_repository->setUserRoles($user->id, [
            9999,
        ]);
    }

    public function test_remove_user(): void
    {
        $user = User::factory()->user()->create(['email' => 'user@test.pl']);

        $user_repository = new UserRepository();
        $user_repository->remove($user->id);

        $this->assertModelMissing($user);
    }
}
