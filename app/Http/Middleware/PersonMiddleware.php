<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = User::where('email', $request->email)->first() ?? $request->user();
        if($user){
            if($user->role->role_name == 'person'){
                return $next($request);
            }else{
                return response()->json([
                    'state' => false,
                    'message' => 'Not Allowed To Login From Here',
                ], 401);
            }
        }else{
            return response()->json([
                'state' => false,
                'message' => 'You Don\'t Have Accout Please Registe With Us',
            ], 401);
        }
    }
}
