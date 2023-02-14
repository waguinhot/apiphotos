<?php

namespace App\Actions\Register;

use App\Actions\AsAction;
use App\Models\User;

class RegisterUser extends AsAction
{
    public function handle(
        ?string $name = null,
        ?string $email = null,
        ?string $verify = null,
        ?string $password = null,
        ?int $type = 0
    ): User {
        return User::create([
         'name' => $name,
         'email' => $email,
         'verify' => $verify,
         'password' => $password,
         'type' => $type,
        ]);
    }
}
