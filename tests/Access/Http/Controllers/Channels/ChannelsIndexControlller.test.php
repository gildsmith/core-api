<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Http\Controllers\Channels\ChannelsIndexController;

covers(ChannelsIndexController::class);

it('allows admin access', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/channels')
        ->assertStatus(200);
});

it('does not allow user access', function () {
    $user = (new UserFactory)->create();

    $this->actingAs($user)
        ->get('api/channels')
        ->assertStatus(403)
        ->assertJson(['message' => 'This action is unauthorized.']);
});

it('does not allow guest access', function () {
    $this->get('api/channels')
        ->assertStatus(403)
        ->assertJson(['message' => 'This action is unauthorized.']);
});
