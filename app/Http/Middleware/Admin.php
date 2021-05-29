<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->role == 'admin') {
                return $next($request);
            }
            elseif (Auth::user()->role == 'superAdmin') {
                return $next($request);
            }
            else{
                Auth::logout();
                return redirect()->route('login.list')->with('error','You are not an Admin.');
            }

        } else {
            return redirect() -> route('login.list');
        }
    }
}