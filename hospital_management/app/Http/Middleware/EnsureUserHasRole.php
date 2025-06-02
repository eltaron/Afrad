<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|array  $roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        $userRoles = Config::get('roles', []); // Fetch roles from config/roles.php

        foreach ($roles as $role) {
            // Check if the role from the route/middleware parameter exists in our defined roles
            // and if the user's role matches that defined role value.
            if (isset($userRoles[$role]) && $user->role === $userRoles[$role]) {
                return $next($request);
            }
        }

        // If user doesn't have any of the required roles
        // You can customize this response, e.g., abort(403, 'Unauthorized action.');
        // Or redirect them to a specific page.
        return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
