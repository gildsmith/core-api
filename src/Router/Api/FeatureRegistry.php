<?php

namespace Gildsmith\HubApi\Router\Api;

/**
 * Manages the registry of available API features. Features represent
 * distinct API functionalities within the Gildsmith system.
 */
class FeatureRegistry
{
    /** @var array Stores the collection of registered features */
    protected static array $registry = [];

    /**
     * Adds a new feature to the registry. The name of
     * the feature should be lowercase and preferably
     * alphanumeric by convention.
     */
    public static function add(string $feature): void
    {
        $feature = strtolower($feature);
        if (!in_array($feature, self::$registry)) {
            self::$registry[] = $feature;
        }
    }

    /**
     *  Returns an array of all registered features, excluding those
     *  specified in the 'features_blacklist' configuration setting.
     */
    public static function get(): array
    {
        $blacklist = config('gildsmith.features_blacklist', []);
        $blacklist = array_map('strtolower', $blacklist);

        return array_diff(self::$registry, $blacklist);
    }
}