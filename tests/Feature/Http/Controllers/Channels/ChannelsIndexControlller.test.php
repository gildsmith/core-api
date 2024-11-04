<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Http\Controllers\Channels\ChannelsIndexController;
use Gildsmith\CoreApi\Models\Channel;

covers(ChannelsIndexController::class);

it('returns all channels', function () {
    $user = (new UserFactory)->admin()->create();

    Channel::factory()->count(5)->create();

    $this->actingAs($user)
        ->get('api/channels')
        ->assertJsonCount(6);
});

it('has correct format', function () {
    $user = (new UserFactory)->admin()->create();

    $this->actingAs($user)
        ->get('api/channels')
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'maintenance',
                'default_currency' => ['id', 'code', 'decimal'],
                'default_language' => ['id', 'code'],
                'currencies' => [
                    '*' => ['id', 'code', 'decimal'],
                ],
                'languages' => [
                    '*' => ['id', 'code'],
                ],
            ],
        ]);
});
