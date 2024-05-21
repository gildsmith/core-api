<?php

namespace Gildsmith\HubApi\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Channel extends Model
{
    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class);
    }
}