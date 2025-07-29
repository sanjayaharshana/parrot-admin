<?php

namespace Modules\UserPanel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Userpanel
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
