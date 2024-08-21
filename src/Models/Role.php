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
    protected $fillable = ['name'];

    public $timestamps = false;

    public function users(): Relation
    {
        return $this->hasMany(User::class);
    }
}
