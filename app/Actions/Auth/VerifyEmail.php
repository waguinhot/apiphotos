<?php

namespace App\Actions\Auth;

use App\Actions\AsAction;
use App\Models\User;

class verifyEmail extends AsAction
{
    public function handle(?string $email = null): User|null
    {
        return User::where('email', $email)->first();
    }
}
