<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restreint une route a l'espace Bureau Executif (Administrateur).
 * La granularite fine (qui peut valider/refuser, publier, etc.) est geree
 * ensuite par les permissions Spatie (middleware `permission:...`).
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            abort(403, "Accès réservé au Bureau Exécutif de LTA.");
        }

        return $next($request);
    }
}
