<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChecksExpiredConfirmationTokens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $redirect)
    {
        // dd($request->confirmation_token);


        if ($request->confirmation_token->hasExpired()) {
            return redirect($redirect)->withError('Token expired.');
        }
        return $next($request);
    }
}
