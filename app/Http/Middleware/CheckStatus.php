<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);

        if (Auth::check()) {
            $user = auth()->user();
            if ($user->status && $user->ev && $user->sv) {
                return $next($request);
            } else {
                return to_route('user.authorization');
            }
        }
        return to_route('user.authorization');
    }
}
