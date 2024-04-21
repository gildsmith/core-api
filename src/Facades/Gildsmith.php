<?php

namespace Gildsmith\HubApi\Facades;

use Illuminate\Support\Facades\Facade;

class Gildsmith extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gildsmith';
    }
}