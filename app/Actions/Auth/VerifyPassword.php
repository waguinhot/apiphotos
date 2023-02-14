<?php

namespace App\Actions\Auth;

use App\Actions\AsAction;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class verifyPassword extends AsAction
{
    public function handle(?User $user = null , ?string $password = null): bool
    {
        if(Hash::check($password, $user->password))
        {
            return true;
        }

        return false;
    }
}
