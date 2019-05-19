<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TokenView extends Middleware {
    public function handle($request, Closure $next, ...$guards) {
        if (Auth::check()) {
            $api_token = auth()->user()->api_token;
            $user_id = auth()->user()->id;
        } else {
            $api_token = User::find(env('PUBLIC_USER_ID', 2))->api_token;
            $user_id = env('PUBLIC_USER_ID', 2);
        }

        // Give the tokens to the view
        View::share('api_token', $api_token);
        View::share('user_id', $user_id);

        return $next($request);
    }
}
