<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $theme = session('theme', 'light');
        if ($theme === 'dark') {
            $request->attributes->add(['theme' => 'dark']);
        } else {
            $request->attributes->add(['theme' => 'light']);
        }

        return $next($request);
    }
}
