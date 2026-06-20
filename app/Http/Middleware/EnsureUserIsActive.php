<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
   public function handle(Request $request, Closure $next)
    {
        // Use the "web" guard explicitly
        $user = Auth::guard('web')->user();

        if ($user && !$user->is_active) {
            Auth::guard('web')->logout(); // Correct way

            return redirect()->route('login')
                ->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
