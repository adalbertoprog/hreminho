<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Se o utilizador autenticado tiver must_change_password = true,
     * redireciona para a página de alteração de password.
     * Permite o logout e o próprio PUT /password sem redirecionar.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Permitir logout e a rota de alteração de password
            $allowed = [
                route('logout'),
                route('password.update'),
            ];

            if ($request->method() === 'POST' && $request->url() === route('logout')) {
                return $next($request);
            }

            if ($request->method() === 'PUT' && $request->url() === route('password.update')) {
                return $next($request);
            }

            // Não redirecionar em ciclo se já está na rota de password
            if (!$request->routeIs('password.change')) {
                return redirect()->route('password.change')
                    ->with('warning', 'Por segurança, deve alterar a sua password antes de continuar.');
            }
        }

        return $next($request);
    }
}
