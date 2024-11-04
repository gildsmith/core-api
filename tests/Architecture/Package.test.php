<?php

declare(strict_types=1);

arch('factories')
    ->expect('Gildsmith\CoreApi\Database\Factories')
    ->toExtend('Illuminate\Database\Eloquent\Factories\Factory')
    ->toHaveSuffix('Factory')
    ->toHaveMethod('definition');

arch('controllers')
    ->expect('Gildsmith\CoreApi\Http\Controllers')
    ->toExtend('Gildsmith\CoreApi\Http\Controllers\Controller')
    ->toHaveSuffix('Controller')
    ->toHaveMethod('__invoke')
    ->ignoring('Gildsmith\CoreApi\Http\Controllers\Controller');
