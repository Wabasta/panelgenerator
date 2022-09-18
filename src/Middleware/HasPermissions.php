<?php

namespace Wabasta\PanelBuilder\Middleware;

use Illuminate\Contracts\Auth\Guard;
use Closure;

class HasPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() != null && $request->user()->permissionCan($request)) {
            return $next($request);
        }

        abort(403);

        return false;
    }
}
