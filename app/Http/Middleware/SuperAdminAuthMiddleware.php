<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SuperAdminAuthMiddleware
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
        // if (!admin()) {
        //     return Redirect::route("login");
        // }

        // if (admin()->type === 'admin') {
        //     return Redirect::route("admin.dashboard.index");
        // }

        if (!Auth::guard('super_admin')->check()) {
            return Redirect::route("super-admin.getlogin");
        }
        return $next($request);
    }
}
