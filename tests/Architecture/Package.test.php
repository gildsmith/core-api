<?php

arch('factories')
    ->expect('Gildsmith\CoreApi\Database\Factories')
    ->toExtend('Illuminate\Database\Eloquent\Factories\Factory')
    ->toHaveSuffix('Factory');

