<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\LanguagesIndexController;

covers(LanguagesIndexController::class);

it('returns a JSON response', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/gildsmith/languages')
        ->assertHeader('Content-Type', 'application/json');
});

it('each language item has an id and code', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/gildsmith/languages')
        ->assertJsonStructure(['*' => ['id', 'code']]);
});
