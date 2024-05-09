<?php

namespace Gildsmith\HubApi\Router\Web;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebRouter
{
    /**
     * Handles web routes by rendering the appropriate web application template.
     */
    public function __invoke(Request $request, string $route): Response
    {
        $webapp = WebRegistry::get($route);

        return empty($webapp->restricted) || in_array($request->user()?->role->name, $webapp->restricted)
            ? response()->view($webapp->template, compact('webapp'))
            : response()->redirectTo('/');
    }
}
