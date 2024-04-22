<?php

namespace Gildsmith\HubApi\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to ensure all responses for API routes are in
 * JSON format. Applied automatically to all API routes.
 */
class ForceJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}