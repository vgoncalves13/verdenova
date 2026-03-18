<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Octane\Exceptions\DdException;
use Symfony\Component\HttpFoundation\Response;
use Webkul\Theme\Facades\Themes;

class SetTheme
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Themes::set('ecovasos-theme');

        return $next($request);
    }
}
