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
    /** @var AppBuilder[] Stores the registered web applications */
    protected static array $registry = [];

    /** @var AppBuilder The default template used as a fallback */
    protected static AppBuilder $fallbackApplication;

    /**
     * Initializes the registry by loading web
     * applications from the Gildsmith configuration.
     */
    public static function init(): void
    {
        self::$fallbackApplication = self::$fallbackApplication ?? new AppBuilder;
    }

    /**
     * Finds and returns the first web application whose route
     * matches the given route, or the fallback web application.
     */
    public static function get(string $route): ?AppBuilder
    {
        $route = strstr($route, '/', true) ?: $route;
        foreach (self::$registry as $app) {
            if ($app->route === $route) {
                return $app;
            }
        }

        return self::fallback();
    }

    public static function fallback(): AppBuilder
    {
        return self::$fallbackApplication;
    }

    public static function getRegistry(): array
    {
        return self::$registry;
    }

    public static function setFallback(AppBuilder $webApplication): void
    {
        self::$fallbackApplication = $webApplication;
    }

    public static function add(AppBuilder $app): void
    {
        self::$registry[] = $app;
    }
}
