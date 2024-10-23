<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Models;

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property Role $role
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'email',
        'password',
    ];

    protected $guarded = [
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = ['role'];

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (! $user->role_id) {
                $user->role_id = self::default()->role_id;
            }
        });
    }

    public static function default(): self
    {
        $user = new self;
        $user->role_id = UserRoleEnum::USER->id();

        return $user;
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    public function role(): Relation
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role->name === $role;
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
