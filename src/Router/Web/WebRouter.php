<?php

namespace Gildsmith\HubApi\Router\Web;

use Symfony\Component\HttpFoundation\Response;

class WebRouter
{
    /**
     * Handles web routes by rendering the appropriateweb application template.
     */
    public function __invoke(string $route): Response
    {
        $webapp = WebRegistry::get($route);
        return response()->view($webapp->template, compact('webapp'));
    }
}