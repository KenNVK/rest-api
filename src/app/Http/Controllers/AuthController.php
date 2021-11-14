<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApiRegisterRequest;
use App\Http\Requests\ApiLoginRequest;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function register(ApiRegisterRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'user' => $user,
            'message' => 'User created successfully'
        ], 200);
    }

    public function login(ApiLoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        } else {
            $user = User::whereEmail($request->email)->first();
            $token = $user->createToken('Myapp')->accessToken;
            return response()->json([
                'user' => $user,
                'token' => $token
            ], 200);
        }
    }

    public function userInfo()
    {
        return response()->json(['user' => Auth::user()], 200);
    }
}
