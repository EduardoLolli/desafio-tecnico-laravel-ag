<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthMode
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $isDevelopment = app()->environment('local', 'dev', 'test');
        $authDisabled = config('auth.enabled') === false;

        if ($isDevelopment && $authDisabled) {
            return $next($request);
        }

        if (!auth('api')->check()) {
            return response()->json([
                'error' => 'Não autorizado'
            ], 401);
        }

        return $next($request);
    }
}
