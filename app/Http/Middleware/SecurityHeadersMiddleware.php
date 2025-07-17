<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeadersMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->has('Content-Type') &&
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; font-src 'self' https://fonts.bunny.net;");
            $response->headers->set('Permissions-Policy', "geolocation=(self), microphone=()");
        }

        return $response;
    }
}
