<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * External sources used by this application:
     *
     * - Alpine.js / Livewire / Filament → require 'unsafe-inline' + 'unsafe-eval' for scripts
     * - Bunny Fonts (Vite plugin)       → fonts.bunny.net
     * - Google Fonts (department page)  → fonts.googleapis.com, fonts.gstatic.com
     * - Vite dev server (local only)    → 127.0.0.1:5173 / localhost:5173
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // -----------------------------------------------------------------
        // HSTS – only when Laravel sees HTTPS
        // -----------------------------------------------------------------
        if ($request->isSecure()) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload',
            );
        }

        // -----------------------------------------------------------------
        // CSP – build directives
        // -----------------------------------------------------------------
        $isLocal = app()->isLocal();

        // Vite dev server origins (HMR websocket + asset serving)
        $viteDev = $isLocal
            ? ' http://127.0.0.1:5173 http://localhost:5173 ws://127.0.0.1:5173 ws://localhost:5173'
            : '';

        $csp = implode(' ', array_filter([
            // Fallback for any resource type not explicitly listed
            "default-src 'self';",

            // Scripts: Alpine.js & Livewire need inline + eval
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob:{$viteDev};",

            // Stylesheets: Filament injects inline styles; Bunny & Google fonts
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net{$viteDev};",

            // Fetch / XHR / WebSocket: Livewire polling, Vite HMR
            "connect-src 'self' blob:{$viteDev};",

            // Fonts: Google, Bunny, data-URIs for inline icon fonts
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net;",

            // Images: data-URIs, blobs for canvas export
            "img-src 'self' data: blob:;",

            // Web Workers: Filament / JS blobs
            "worker-src 'self' blob:;",

            // Prevent clickjacking
            "frame-ancestors 'self';",

            // Lock <base> tag
            "base-uri 'self';",

            // Only upgrade HTTP→HTTPS when actually serving over HTTPS or in production
            ($request->isSecure() || app()->isProduction()) ? 'upgrade-insecure-requests;' : null,
        ]));

        $response->headers->set('Content-Security-Policy', $csp);

        // -----------------------------------------------------------------
        // Other security headers
        // -----------------------------------------------------------------
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy',
            'geolocation=(self), camera=(), microphone=(), fullscreen=(self), payment=()',
        );

        return $response;
    }
}
