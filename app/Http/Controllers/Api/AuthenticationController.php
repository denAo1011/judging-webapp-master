<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    /**
     * Login
     */
    public function login(LoginRequest $request)
    {
        $validatedEmail = $request->validated()['email'];
        $validatedPassword = $request->validated()['password'];

        $user = User::where('email', $validatedEmail)->firstOrFail();

        if (Hash::check($validatedPassword, $user->password)) {
            $token = $user->createToken('LaravelPasswordGrantClient')->accessToken;
            return response($token);
        }

        return response('Invalid Credentials', 401);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $token = request()->user()->token();
        $token->revoke();
        return response(null, 204);
    }
}
