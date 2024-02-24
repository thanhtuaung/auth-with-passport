<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    // register
    public function register(RegisterUserRequest $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $accessToken = $user->createToken('auth_token')->accessToken;
        return response(['access_token' => $accessToken]);
    }

    // login
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response(['message' => 'Invalid login credentials'], 404);
        }

        $accessToken = $user->createToken('auth_user')->accessToken;
        return response(['access_token' => $accessToken]);
    }

    // logout
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response(['message' => 'Logout successfully!']);
    }
}
