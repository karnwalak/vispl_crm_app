<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Response\LoginResponse;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(LoginRequest $request) {
        $Credentials = $request->only('email', 'password');
        if(Auth::attempt($Credentials)) {
            $Token = Str::random(80);
            $LoginResponse = new LoginResponse(true, null, $Token);
            // Update the token in users table
            User::where('email', $request->email)->update([
                'api_token' => $Token,
            ]);
            return response()->json($LoginResponse->toJson());
        } else {
            $LoginResponse = new LoginResponse(false, null, null);
            return response()->json($LoginResponse->toJson());
        }
    }
}
