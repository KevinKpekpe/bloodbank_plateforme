<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'donor' && !$user->isDonor()) {
            abort(403, 'Accès réservé aux donneurs.');
        }

        if ($role === 'admin_banque' && !$user->isBankAdmin()) {
            abort(403, 'Accès réservé aux administrateurs de banque.');
        }

        if ($role === 'superadmin' && !$user->isSuperAdmin()) {
            abort(403, 'Accès réservé au super administrateur.');
        }

        return $next($request);
    }
}