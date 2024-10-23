<?php

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadLanguages;

covers(ReadLanguages::class);

it('returns a JSON response', function () {
    $user = (new UserFactory())->admin()->create();

    $this->actingAs($user)
        ->get('/_gildsmith/languages')
        ->assertHeader('Content-Type', 'application/json');
});

it('each language item has an id and code', function () {
    $user = (new UserFactory())->admin()->create();

    $this->actingAs($user)
        ->get('/_gildsmith/languages')
        ->assertJsonStructure(['*' => ['id', 'code']]);
});