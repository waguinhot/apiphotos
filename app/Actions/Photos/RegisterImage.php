<?php

namespace App\Actions\Photos;

use App\Actions\AsAction;
use App\Models\Photo;

class RegisterImage extends AsAction
{
    public function handle(
        ?string $name = null,
        ?string $imageName = null,
        ?int $user_id = null,
        ?int $status = 0
    ): Photo {
        return Photo::create([
            'name' => $name,
            'user_id' => $user_id,
            'url' => $imageName,
            'status' => $status,
        ]);
    }
}
