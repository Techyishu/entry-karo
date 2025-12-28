<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CanDeleteEntry
{
    /**
     * Handle an incoming request.
     *
     * Only super admin can delete entries.
     * Customers can view entries but cannot delete.
     * Guards can manage entries (check-in/out) but cannot delete.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Only super admin can delete entries
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Access denied. Only super admin can delete entries.');
        }

        return $next($request);
    }
}
