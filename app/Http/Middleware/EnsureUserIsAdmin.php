<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     * Ensure only admin/superadmin can access admin routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Check if user has admin or superadmin role
        if (!$user->hasRole(['admin', 'superadmin'])) {
            // If pemerintah user tries to access admin, redirect to their dashboard
            if ($user->hasRole('pemerintah')) {
                return redirect()->route('pemerintah.dashboard')->with('error', 'Anda tidak memiliki akses ke area admin.');
            }
            
            // If akademisi user tries to access admin, redirect to their dashboard
            if ($user->hasRole('akademisi')) {
                return redirect()->route('akademisi.dashboard')->with('error', 'Anda tidak memiliki akses ke area admin.');
            }
            
            // For any other case, abort with 403
            abort(403, 'Akses ditolak. Hanya admin yang dapat mengakses area ini.');
        }

        return $next($request);
    }
}