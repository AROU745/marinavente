<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminBoutiqueAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            $request->session()->put('admin_boutique_auth', true);
            return $next($request);
        }

        if ($request->session()->get('admin_boutique_auth')) {
            return $next($request);
        }

        return redirect()
            ->route('admin.login')
            ->with('error', 'Connectez-vous pour accéder à l\'administration.');
    }
}
