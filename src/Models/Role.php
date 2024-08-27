<?php

namespace Gildsmith\HubApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

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
}
