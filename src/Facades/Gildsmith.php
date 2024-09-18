<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Facades;

use Gildsmith\CoreApi\Router\Api\ApiFeatureBuilder;
use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void api()
 * @method static void web()
 * @method static WebAppBuilder app(?string $name = null)
 * @method static ApiFeatureBuilder feature(string $feature)
 */
class Gildsmith extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gildsmith';
    }
}
