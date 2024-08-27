<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property int $id
 * @property string $code
 * @property int $decimal
 */
class Currency extends Model
{
    protected $fillable = ['code'];

    public function channel(): Relation
    {
        return $this->hasMany(Channel::class);
    }
}
