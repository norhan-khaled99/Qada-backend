<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function register(Request $request)
    {
        $input = $request->all();
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'phone' => 'string|unique:users,phone',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'state' => false,
                'data' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        } else {
            $input['role_id'] = 3;
            $input['password'] = Hash::make($request->password);
            $otp = '';
            for (
                $i = 0;
                $i < 4;
                $i++
            ) {
                $otp .= random_int(0, 9);
            }
            $input['otp'] = Hash::make($otp);
            $user = User::create($input);
            $data = [
                'request' => $otp
            ];
            Mail::send('emails.otp', $data, function ($message) use ($request, $user) {
                $message->to($user->email);
                $message->subject('Qada Mail Confirmation');
            });
            return response()->json([
                'state' => true,
                'data' => 'User Created Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        }
    }
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
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
                    'data' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'state' => false,
                    'data' => 'Email & Password does not match with our record.',
                ], 401);
            }
            $user = User::where('email', $request->email)->first();
            $role = Role::find($user->role_id);
            $user['role'] = $role->role_name;
            return response()->json([
                'state' => true,
                'data' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'state' => false,
                'data' => $th->getMessage()
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
                'data' => $th->getMessage(),
            ], 401);
        }
    }
    function otpConfirmation(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->active == 1) {
            return response()->json([
                'state' => false,
                'data' => 'Account is Already Active ',
            ], 200);
        }
        $otp = $request->otp;
        if (Hash::check($otp, $user->otp)) {
            $user->update([
                'active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
            return response()->json([
                'state' => true,
                'data' => $user,
            ], 200);
        }
    }
}
