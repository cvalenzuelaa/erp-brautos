<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Si el usuario tiene rol "super_admin", pasa todo
        if (auth()->user()->rol === 'super_admin') {
            return $next($request);
        }

        if (!in_array(auth()->user()->rol, $roles)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}