<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $allowed = explode('|', $roles);

        if (! in_array($request->user()->role, $allowed, true)) {
            abort(403);
        }

        return $next($request);
    }
}
