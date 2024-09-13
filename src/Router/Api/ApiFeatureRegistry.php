<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Router\Api;

class ApiFeatureRegistry
{
    protected static array $registry = [];

    /**
     * Appends a new feature builder to the registry
     * with its associated route builder.
     */
    public static function add(string $feature, ApiFeatureBuilder $apiFeatureRoutes): void
    {
        if (!in_array($feature, self::$registry)) {
            self::$registry[$feature] = [];
        }

        self::$registry[$feature][] = $apiFeatureRoutes;
    }

    /**
     * Registers all collected feature routes.
     */
    public static function call(): void
    {
        foreach (self::$registry as $feature) {

            /** @var ApiFeatureBuilder $builder */
            foreach ($feature as $builder) {
                ($builder->build())();
            }
        }
    }
}
