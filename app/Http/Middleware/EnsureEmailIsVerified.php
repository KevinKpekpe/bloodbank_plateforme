<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('EnsureEmailIsVerified@handle - Request URL: ' . $request->url());
        Log::info('EnsureEmailIsVerified@handle - User authenticated: ' . (Auth::check() ? 'true' : 'false'));

        if (Auth::check()) {
            $user = Auth::user();
            Log::info('EnsureEmailIsVerified@handle - User ID: ' . $user->id . ', Role: ' . $user->role . ', Email verified: ' . ($user->isEmailVerified() ? 'true' : 'false'));

            if ($user->isDonor() && !$user->isEmailVerified()) {
                Log::info('EnsureEmailIsVerified@handle - Redirecting to verification notice');
                return redirect()->route('verification.notice');
            }
        }

        Log::info('EnsureEmailIsVerified@handle - Allowing request to continue');
        return $next($request);
    }
}
