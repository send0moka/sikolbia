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
            
            // Skip redirect if already on the correct panel or public routes
            if ($request->is('pemerintah/*') || 
                $request->is('akademisi/*') || 
                $request->is('admin/*') || 
                $request->is('dashboard') ||
                $request->is('registrasi/*') ||
                $request->is('ketersediaan/*') ||
                $request->is('logout')) {
                return $next($request);
            }
            
            // Check role dan redirect ke panel yang sesuai HANYA untuk root atau home requests
            if ($user->hasRole('pemerintah')) {
                // Pemerintah → Pemerintah Dashboard
                return redirect()->route('pemerintah.dashboard');
            } elseif ($user->hasRole('akademisi')) {
                // Akademisi → Akademisi Dashboard
                return redirect()->route('akademisi.dashboard');
            } elseif ($user->hasRole('superadmin') || $user->hasRole('admin')) {
                // Admin/Superadmin → Admin Panel Selection
                return redirect()->route('admin.panel-selection');
            }
        }
        
        return $next($request);
    }
}
