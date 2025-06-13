<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        $userRole = $request->user()->role;
        
        if (!in_array($userRole, $roles)) {
            switch ($userRole) {
                case 'ADMIN':
                    return redirect()->route('admin.dashboard');
                case 'CONSEILLER_FINANCIER':
                    return redirect()->route('advisors.dashboard');
                default:
                    return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
} 