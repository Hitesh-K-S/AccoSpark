<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle($request, Closure $next)
    {
        if (! Auth::check() || ! Auth::user()->is_admin) {
            return redirect('/overwatch/login');
        }

        return $next($request);
    }
}
