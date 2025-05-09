<?php

namespace Totocsa\Icseusd\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleIsAdministrator
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->hasRole('Administrator')) {
            //abort(403, 'Access denied');
            return redirect()->route('/');
        }

        return $next($request);
    }
}
