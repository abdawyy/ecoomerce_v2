<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle(Request $request, Closure $next)
{
    // Check if the user is authenticated as an admin
    if (!Auth::guard('admin')->check()) {
        return redirect()->route('admin.login')->with('error', 'Please login as admin.');
    }

    // Check if the admin user is active
    $admin = Auth::guard('admin')->user();
    if (!$admin->is_active) {
        Auth::guard('admin')->logout(); // Log out the inactive user
        return redirect()->route('admin.login')->with('error', 'Your account is inactive.');
    }

    // If authenticated and active, allow the request to proceed
    return $next($request);
}

}
