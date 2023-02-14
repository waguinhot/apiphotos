<?php

namespace App\Actions;

abstract class AsAction
{
    abstract public function handle();

    public static function run(...$arguments)
    {
        return app(static::class)->handle(...$arguments);
    }
}
