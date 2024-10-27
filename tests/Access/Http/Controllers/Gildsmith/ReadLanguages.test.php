<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\LanguagesIndexController;
use Gildsmith\CoreApi\Models\User;

covers(LanguagesIndexController::class);

it('allows admin access', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/gildsmith/languages')
        ->assertStatus(200);
});

it('does not allow user access', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('api/gildsmith/languages')
        ->assertStatus(403);
});
