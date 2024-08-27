<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Router\Web;

/**
 * Manages a collection of registered web (frontend) applications
 * within the Gildsmith system. This registry facilitates matching
 * an incoming route to its corresponding web application.
 */
class WebRegistry
{
    /** @var WebApplication[] Stores the registered web applications */
    protected static array $registry = [];

    /** @var WebApplication The default template used as a fallback */
    protected static WebApplication $fallbackApplication;

    /**
     * Initializes the registry by loading web
     * applications from the Gildsmith configuration.
     */
    public static function init(): void
    {
        self::$fallbackApplication = self::$fallbackApplication
            ?? new WebApplication('storefront', '', config('gildsmith.default', 'gildsmith.template'));
    }

    /**
     * Finds and returns the first web application whose route
     * matches the given route, or the fallback web application.
     */
    public static function get(string $route): ?WebApplication
    {
        $route = strstr($route, '/', true) ?: $route;
        foreach (self::$registry as $app) {
            if ($app->route === $route) {
                return $app;
            }
        }

        return self::fallback();
    }

    public static function fallback(): WebApplication
    {
        return self::$fallbackApplication;
    }

    public static function getRegistry(): array
    {
        return self::$registry;
    }

    public static function setFallback(WebApplication $webApplication): void
    {
        self::$fallbackApplication = $webApplication;
    }

    public static function add(WebApplication $app): void
    {
        self::$registry[] = $app;
    }
}
