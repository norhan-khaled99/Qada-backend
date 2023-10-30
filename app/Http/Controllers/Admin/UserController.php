<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function create(Request $request){
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
                'phone' => 'required|unique:users,phone',
                'role_id' => 'required',
                'name' => 'required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'state' => false,
                'errors' => $validateUser->errors()
            ], 401);
        }else{
            $user = User::create([
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'name' => $request->name,
                'phone' => $request->phone,
                'active' => 1,
                'email_verified_at' => Carbon::now(),
                'role_id' => $request->role_id,
            ]);
            return response()->json([
                'state' => true,
                'data' => $user,
            ], 200);
        }
    }
}
