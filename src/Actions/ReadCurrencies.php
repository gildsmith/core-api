<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions;

use Gildsmith\CoreApi\Models\Currency;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;

class ReadCurrencies extends Action
{
    public function authorize(Request $request): bool
    {
        return $request->user() && $request->user()->role->name === 'admin';
    }

    public function handle(): Collection
    {
        return Currency::all();
    }
}
