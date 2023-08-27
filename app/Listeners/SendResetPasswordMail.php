<?php

namespace App\Listeners;

use App\Events\PasswordForgotten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Password;

class SendResetPasswordMail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(PasswordForgotten $event): void
    {
        $token = Password::getRepository()->create($event->user);
        $event->user->sendPasswordResetNotification($token);
    }
}
