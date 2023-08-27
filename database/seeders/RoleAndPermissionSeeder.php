<?php

namespace Database\Seeders;

use App\Constants\Permissions;
use App\Constants\Roles;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create([

            'name' => Permissions::CREATE_USERS,
        ]);
        Permission::create([

            'name' => Permissions::EDIT_USERS,
        ]);
        Permission::create([

            'name' => Permissions::LIST_USERS,
        ]);
        Permission::create([

            'name' => Permissions::DELETE_USERS,
        ]);
        Permission::create([

            'name' => Permissions::SET_USER_ROLES,
        ]);

        Permission::create([

            'name' => Permissions::LIST_POSTS,
        ]);
        Permission::create([

            'name' => Permissions::CREATE_POSTS,
        ]);
        Permission::create([

            'name' => Permissions::EDIT_POSTS,
        ]);
        Permission::create([

            'name' => Permissions::DELETE_POSTS,
        ]);

        $adminRole = Role::create([

            'name' => Roles::ADMIN,
        ]);
        $editorRole = Role::create([

            'name' => Roles::EDITOR,
        ]);
        $userRole = Role::create([

            'name' => Roles::USER,
        ]);

        $userPermissions = [
            Permissions::LIST_POSTS,
        ];
        $editorPermissions = [
            Permissions::CREATE_POSTS,
            Permissions::EDIT_POSTS,
            Permissions::DELETE_POSTS,
        ];
        $adminPermissions = [
            Permissions::CREATE_USERS,
            Permissions::SET_USER_ROLES,
            Permissions::LIST_USERS,
            Permissions::EDIT_USERS,
            Permissions::DELETE_USERS,
        ];

        $adminRole->givePermissionTo([
            ...$userPermissions,
            ...$editorPermissions,
            ...$adminPermissions,
        ]);

        $editorRole->givePermissionTo([
            ...$userPermissions,
            ...$editorPermissions,
        ]);

        $userRole->givePermissionTo($userPermissions);
    }
}
