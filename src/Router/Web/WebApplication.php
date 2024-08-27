<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Router\Web;

/**
 * Represents a web application (encapsulated frontend app) within
 * the Gildsmith system. Stores essential information for rendering
 * and routing the application.
 */
readonly class WebApplication
{
    /**
     * @param  string  $identifier  A unique name to identify the web application.
     * @param  string  $route  The top-level URL path where the application is accessible.
     * @param  string  $template  The name of the template file used to render the application's entry point.
     * @param  array  $params  Optional custom data to be passed to the template during rendering.
     * @param  array  $restricted  Allow only users with certain roles to access the app.
     */
    public function __construct(
        public string $identifier,
        public string $route,
        public string $template,
        public array $params = [],
        public array $restricted = [],
    ) {}
}
