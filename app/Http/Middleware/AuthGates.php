<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            $permissions = Permission::all();
            foreach ($permissions as $permission) {
                Gate::define($permission->code, function ($user) use ($permission) {
                    $adminUser = Auth::guard('admin')->user();
                    return $adminUser && $adminUser->hasPermission($permission->code);
                });
            }
        }

        return $next($request);
    }
}
