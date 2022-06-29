<?php

namespace App\Http\Controllers\API;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;

class UserController extends Controller
{
    public function index()
	{
        $users = User::all();

        return response()->json([
            new UserResourceCollection($users),
        ], 201);

	}
}
