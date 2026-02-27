<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            abort(403, 'No autorizado');
        }

        // Soporta role:admin|alumno y role:admin,alumno
        $allowed = [];
        foreach ($roles as $r) {
            foreach (preg_split('/[|,]/', $r) as $piece) {
                $piece = trim($piece);
                if ($piece !== '') $allowed[] = $piece;
            }
        }

        if (!in_array(auth()->user()->role, $allowed, true)) {
            abort(403, 'No autorizado');
        }

        return $next($request);
    }
}