<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Database\Factories;

use Gildsmith\CoreApi\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company,
            'description' => fake()->text,
            'default_currency_id' => 154,
            'default_language_id' => 37,
            'maintenance' => false,
        ];
    }
}
