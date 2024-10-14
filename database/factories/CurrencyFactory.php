<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Database\Factories;

use Gildsmith\CoreApi\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->currencyCode(),
            'decimal' => $this->faker->numberBetween(0, 4),
        ];
    }
}
