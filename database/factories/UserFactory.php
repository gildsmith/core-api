<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Database\Factories;

use Gildsmith\CoreApi\Enums\UserRoleEnum;
use Gildsmith\CoreApi\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Override;

class UserFactory extends Factory
{
    protected static ?string $password;

    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => UserRoleEnum::USER->id(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => UserRoleEnum::ADMIN->id(),
        ]);
    }

    #[Override]
    public function create($attributes = [], ?Model $parent = null): User
    {
        return parent::create($attributes, $parent);
    }
}
