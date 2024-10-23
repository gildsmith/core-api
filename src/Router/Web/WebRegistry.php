<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Router\Web;

/**
 * Manages a collection of registered web (frontend) applications
 * within the Gildsmith system. This registry facilitates matching
 * an incoming route to its corresponding web application.
 */
class WebRegistry
{
    /** @var WebAppBuilder[] Stores the registered web applications */
    protected static array $registry = [];

    /** @var WebAppBuilder The default template used as a fallback */
    protected static WebAppBuilder $fallbackApplication;

    /**
     * Finds and returns the first web application whose route
     * matches the given route, or the fallback web application.
     */
    public static function get(string $route): ?WebAppBuilder
    {
        $route = strstr($route, '/', true) ?: $route;
        foreach (self::$registry as $app) {
            if ($app->getRoute() === $route) {
                return $app;
            }
        }

        return self::fallback();
    }

    public static function fallback(): WebAppBuilder
    {
        return self::$fallbackApplication;
    }

    public static function registry(): array
    {
        return self::$registry;
    }

    /**
     * Returns all registered web applications,
     * including fallback application.
     *
     * @return WebAppBuilder[]
     */
    public static function getFullRegistry(): array
    {
        WebRegistry::init();

        return [...self::$registry, self::$fallbackApplication];
    }

    /**
     * Initializes the registry by loading web
     * applications from the Gildsmith configuration.
     */
    public static function init(): void
    {
        self::$fallbackApplication = self::$fallbackApplication ?? new WebAppBuilder;
    }

    public static function setFallback(WebAppBuilder $webApplication): void
    {
        self::$fallbackApplication = $webApplication;
    }

    public static function add(WebAppBuilder $app): void
    {
        self::$registry[] = $app;
    }
}
