<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdvisorMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'CONSEILLER_FINANCIER') {
            abort(403, 'Accès réservé aux conseillers financiers.');
        }
        return $next($request);
    }
}
