<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Controllers\Gildsmith;

use Gildsmith\CoreApi\Http\Controllers\Controller;
use Gildsmith\CoreApi\Models\Currency;

class CurrenciesIndexController extends Controller
{
    public function __invoke()
    {
        $this->authorize('role', 'admin');

        return Currency::all();
    }
}
