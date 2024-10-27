<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\CurrenciesIndexController;

covers(CurrenciesIndexController::class);

it('returns a JSON response', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/gildsmith/currencies')
        ->assertHeader('Content-Type', 'application/json');
});

it('each language item has an id and code', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/gildsmith/currencies')
        ->assertJsonStructure(['*' => ['id', 'code', 'decimal']]);
});
