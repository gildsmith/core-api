<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Controllers\Gildsmith;

use Gildsmith\CoreApi\Http\Controllers\Controller;
use Gildsmith\CoreApi\Models\Language;
use Illuminate\Http\Request;

class LanguagesIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('role', 'admin');

        return Language::all();
    }
}
