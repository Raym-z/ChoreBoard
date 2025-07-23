<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            abort(403, 'Access denied. Admin privileges required.');
        }
        $user = Auth::user();
        $householdId = session('current_household_id');
        if (!$householdId) {
            abort(403, 'No household context.');
        }
        $pivot = $user->households()->where('household_id', $householdId)->first()?->pivot;
        if (!$pivot || $pivot->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }
        return $next($request);
    }
}