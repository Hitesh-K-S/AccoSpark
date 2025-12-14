<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateKestra
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Kestra-Token');

        if ($token !== config('services.kestra.token')) {
            abort(401, 'Unauthorized Kestra request');
        }

        return $next($request);
    }
}
