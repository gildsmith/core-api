<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Facades;

use Gildsmith\CoreApi\Router\Api\FeatureBuilder;
use Gildsmith\CoreApi\Router\Web\AppBuilder;
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
