<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     * Redirect users to their appropriate panel based on their role.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check role dan redirect ke panel yang sesuai
            if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
                // Admin/Superadmin → Admin Panel Selection
                if (!$request->is('admin/*') && !$request->is('dashboard')) {
                    return redirect()->route('admin.panel-selection');
                }
            } elseif ($user->hasRole('pemerintah')) {
                // Pemerintah → Pemerintah Dashboard
                if (!$request->is('pemerintah/*')) {
                    return redirect()->route('pemerintah.dashboard');
                }
            } elseif ($user->hasRole('akademisi')) {
                // Akademisi → Akademisi Dashboard
                if (!$request->is('akademisi/*')) {
                    return redirect()->route('akademisi.dashboard');
                }
            }
        }
        
        return $next($request);
    }
}
