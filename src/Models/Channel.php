<?php

namespace Gildsmith\HubApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Channel extends Model
{
    public function currencies(): Relation
    {
        return $this->belongsToMany(Currency::class);
    }

    public function languages(): Relation
    {
        return $this->belongsToMany(Language::class);
    }
}