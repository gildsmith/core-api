<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Facades;

use Gildsmith\HubApi\Router\Api\FeatureBuilder;
use Gildsmith\HubApi\Router\Web\AppBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void api()
 * @method static void web()
 * @method static AppBuilder app(?string $name = null)
 * @method static FeatureBuilder feature(string $feature)
 */
class Gildsmith extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gildsmith';
    }
}
