<?php

namespace App\Http\Middleware;

use Closure;
use App\Constants\Status;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowRegistration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (gs('registration') == Status::DISABLE) {
            $notify[] = ['error', 'Registration is currently disabled'];
            return back()->withNotify($notify);
        }
        return $next($request);
    }
}
