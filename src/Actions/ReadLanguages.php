<?php

namespace Gildsmith\HubApi\Actions;

use Gildsmith\HubApi\Models\Language;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Action;

// todo
class ReadLanguages extends Action
{
    public function handle(): Collection
    {
        return Language::all();
    }
}