<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Database\Factories;

use Gildsmith\CoreApi\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->languageCode(),
        ];
    }
}
