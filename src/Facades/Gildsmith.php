<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Facades;

use Gildsmith\HubApi\Router\Api\ApiFeatureRoutes;
use Gildsmith\HubApi\Router\Web\WebApplication;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void api()
 * @method static void web()
 * @method static WebApplication app(?string $name = null)
 * @method static ApiFeatureRoutes feature(string $feature)
 */
class Gildsmith extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gildsmith';
    }
}
