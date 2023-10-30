<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'state' => false,
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'state' => false,
                    'errors' => 'Email & Password does not match with our record.',
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'state' => true,
                'data' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'state' => false,
                'errors' => $th->getMessage()
            ], 401);
        }
    }
    public function UpdateUser(Request $request)
    {
        try {
            $user = $request->user();
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user = $request->user()->id,
                'password' => 'same:confirm-password',
            ]);
            $input = $request->all();
            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                $input = Arr::except($input, array('password'));
            }
            $user = $request->user();
            $user->update($input);
            return response()->json([
                'state' => true,
                'data' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'state' => false,
                'errors' => $th->getMessage(),
            ], 401);
        }
    }
}
