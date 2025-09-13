<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $swaggerPaths = [
            'api/documentation*',
            'api/oauth2-callback*',
            'docs*',
        ];

        foreach ($swaggerPaths as $path) {
            if ($request->is($path)) {
                return $next($request);
            }
        }

        $apiKey = $request->header('X-API-KEY') ?? $request->query('api_key');
        if ($apiKey !== config('app.api_key')) {
            return response()->json(['error' => 'Ошибка авторизации API'], 401);
        }

        return $next($request);
    }
}
