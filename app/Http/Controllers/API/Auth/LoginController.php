<?php

namespace App\Http\Controllers\API\Auth;

use Auth;
use Hash;
use Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;

use App\Http\Resources\UserResource;

class LoginController extends Controller
{
    public function login(Request $request)
    {
    	$request->validate([
			'email'    => 'required|email',
			'password' => 'required'
		]);

		$user = User::where('email', $request->email)->first();

		if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email or password is wrong'
            ], 401);
            
		}

        $token = $user->createToken('apptoken')->plainTextToken;

        return response()->json([
            'message'   => 'Success',
            'token' => $token,
            'user'      => new UserResource($user)
        ], 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Success'
        ], 201);
    }
}