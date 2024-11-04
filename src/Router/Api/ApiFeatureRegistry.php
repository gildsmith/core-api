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
    public static function add(string $featureIdentifier, ApiFeatureBuilder $apiFeatureRoutes): void
    {
        if (! array_key_exists($featureIdentifier, self::$registry)) {
            self::$registry[$featureIdentifier] = [];
        }

        if (! in_array($apiFeatureRoutes, self::$registry[$featureIdentifier], true)) {
            self::$registry[$featureIdentifier][] = $apiFeatureRoutes;
        }
    }

    /**
     * Registers all collected feature routes.
     */
    public static function call(): void
    {
        array_walk_recursive(self::$registry, function (ApiFeatureBuilder $builder) {
            ($builder->build())();
        });
    }
}
