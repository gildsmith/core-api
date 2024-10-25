<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Utils;

use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;

class ApplicationFilter
{
    /**
     * Handles retrieving the registered applications, filtered by role restrictions.
     * If a role is provided, only returns apps accessible by that role.
     */
    public static function filter(WebRegistry $webRegistry, string $role = 'guest', ?string $app = null): array
    {
        $apps = $webRegistry->getFullRegistry();

        // Filter applications by role
        $apps = $role === null || $role === 'admin' ? $apps : array_filter($apps, function (WebAppBuilder $app) use ($role) {
            return empty($app->getGroups()) || in_array($role, $app->getGroups());
        });

        if ($app !== null) {
            $filteredApps = array_filter($apps, function (WebAppBuilder $registeredApp) use ($app) {
                return $registeredApp->getIdentifier() === $app;
            });

            // Return the specific app or an empty object if not found
            return count($filteredApps) > 0
                ? [array_shift($filteredApps)]
                : [];
        }

        return [...$apps];
    }
}
