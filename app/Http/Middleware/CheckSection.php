<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSection
{
    public function handle(Request $request, Closure $next, string $section): Response
    {
        if (! $request->user() || ! $request->user()->tokenCan($section)) {
            return response()->json([
                'message' => 'Acceso denegado. No tienes la sección o permiso asignado en tu perfil.',
            ], 403);
        }

        return $next($request);
    }
}
