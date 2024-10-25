<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Controllers\Gildsmith;

use Gildsmith\CoreApi\Http\Controllers\Controller;
use Gildsmith\CoreApi\Models\Language;

class ReadLanguages extends Controller
{
    public function __invoke()
    {
        $this->authorize('role', 'admin');

        return Language::all();
    }
}
