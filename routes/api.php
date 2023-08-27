<?php

use App\Constants\Permissions;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [
    RegistrationController::class,
    'store',
]);
Route::post('login', [
    LoginController::class,
    'login',
]);

Route::middleware(['guest'])->group(function () {
    Route::post('forgot-password', [
        PasswordResetController::class,
        'sendResetToken',
    ]);

    Route::post('reset-password', [
        PasswordResetController::class,
        'resetPassword',
    ]);
});

Route::get('posts', [
    PostController::class,
    'index',
]);

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [
        LoginController::class,
        'logout',
    ]);

    Route::post('posts', [
        PostController::class,
        'store',
    ])->middleware('can:' . Permissions::CREATE_POSTS);
    Route::post('posts/remove', [
        PostController::class,
        'remove',
    ])->middleware('can:' . Permissions::DELETE_POSTS);
    Route::post('posts/edit', [
        PostController::class,
        'edit',
    ])->middleware('can:' . Permissions::EDIT_POSTS);


    Route::get('users', [
        UserController::class,
        'index',
    ])->middleware('can:' . Permissions::LIST_USERS);
    Route::post('users', [
        UserController::class,
        'store',
    ])->middleware('can:' . Permissions::CREATE_USERS);
    Route::post('users/edit', [
        UserController::class,
        'edit',
    ])->middleware('can:' . Permissions::EDIT_USERS);
    Route::post('users/remove', [
        UserController::class,
        'remove',
    ])->middleware('can:' . Permissions::DELETE_USERS);
    Route::post('users/set-roles', [
        UserController::class,
        'setRoles',
    ])->middleware('can:' . Permissions::SET_USER_ROLES);
});
