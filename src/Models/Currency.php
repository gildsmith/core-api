<?php

namespace Gildsmith\HubApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property integer $id
 * @property string $code
 * @property integer $decimal
 */
class Currency extends Model
{
    protected $fillable = ['code'];

    public function channel(): Relation
    {
        return $this->hasMany(Channel::class);
    }
}