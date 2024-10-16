<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string name
 */
class Role extends Model
{
    public $timestamps = false;

    protected $fillable = ['name'];

    public function users(): Relation
    {
        return $this->hasMany(User::class);
    }

    public static function default(): Collection
    {
        $user = new self;
        $user->name = 'user';

        $admin = new self;
        $admin->name = 'admin';

        return collect([$user, $admin]);
    }
}
