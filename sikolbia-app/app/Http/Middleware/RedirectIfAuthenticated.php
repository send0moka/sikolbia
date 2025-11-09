<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Redirect based on role
                if ($user->hasRole('pemerintah')) {
                    return redirect()->route('pemerintah.dashboard');
                } elseif ($user->hasRole('akademisi')) {
                    return redirect()->route('akademisi.dashboard');
                } else {
                    // Admin/Superadmin
                    return redirect()->route('admin.panel-selection');
                }
            }
        }

        return $next($request);
    }
}