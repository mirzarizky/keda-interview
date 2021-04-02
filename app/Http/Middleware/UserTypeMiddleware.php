<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

class UserTypeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$types
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        foreach ($types as $type) {
            if ($request->user()->type->name == $type) {
                return $next($request);
            }
        }

        abort(403, 'You don\'t have permission to access this resource.');
    }
}
