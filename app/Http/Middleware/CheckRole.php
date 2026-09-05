<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        if (! in_array($request->user()->role, $roles) && ! in_array('all', $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized: Insufficient role permissions'], 403);
            }
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
