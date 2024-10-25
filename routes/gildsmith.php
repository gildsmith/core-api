<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadApplications;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadCurrencies;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadLanguages;
use Illuminate\Support\Facades\Route;

Route::get('api/gildsmith/currencies', ReadCurrencies::class);
Route::get('api/gildsmith/languages', ReadLanguages::class);

Route::get('api/gildsmith/apps', ReadApplications::class);
Route::get('api/gildsmith/apps/{app}', ReadApplications::class);
