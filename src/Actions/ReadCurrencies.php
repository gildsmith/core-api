<?php

namespace Gildsmith\HubApi\Actions;

use Gildsmith\HubApi\Models\Currency;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Action;

// todo
class ReadCurrencies extends Action
{
    public function handle(): Collection
    {
        return Currency::all();
    }
}