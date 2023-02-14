<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\verifyEmail;
use App\Actions\Auth\verifyPassword;
use App\Actions\Register\RegisterUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct()
    {
    }

    public function login(LoginRequest $request): Response
    {
        $user = verifyEmail::run($request->email);

        if (!$user) {
            return response(json_encode(['email' => ['The provided credentials are incorrect.']]), 401);
        }

        if ($user->verify != 'checked') {
            return response(json_encode(['verify' => ['Please activate your account']]), 401);
        }

        $validUser = verifyPassword::run($user, $request->password);

        if (!$validUser) {
            return response(json_encode(['email' => ['The provided credentials are incorrect.']]), 401);
        }

        return response(json_encode([
            'user' => $user,
            'token' => $user->createToken($request->device_name)->plainTextToken,
            'type' => 'Bearer',
        ]));
    }

    public function register(RegisterRequest $request): Response
    {
        $user = verifyEmail::run($request->email);

        if ($user) {
            return response(json_encode(['email' => ['The email has ben use.']]), 401);
        }

        $str = Str::random(25);

        $user = RegisterUser::run(
            $request->name,
            $request->email,
            $str,
            Hash::make($request->password));

        if (!$user) {
            return response(json_encode([
                'status' => 'error',
                'user' => $user,
            ]), 401);
        }

        return response(json_encode([
            'status' => 'success',
            'user' => $user,
        ]), 201);
    }

    public function verify(string $verify)
    {
        if (!$verify) {
            return;
        }

        $user = User::where('verify', $verify)->first();

        if (!$user) {
            return;
        }

        $user->verify = 'checked';
        $user->save();

        return 'Account active with success';
    }
}
