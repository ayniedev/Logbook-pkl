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
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                abort(401);
            }

            return redirect()->route('login');
        }

        if (! $user->relationLoaded('role')) {
            $user->load('role');
        }

        $roleName = $user->role?->name;

        if (! $roleName || in_array($roleName, $roles, true) === false) {
            abort(403);
        }

        return $next($request);
    }
}
