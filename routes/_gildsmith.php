<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadApplications;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadCurrencies;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadLanguages;
use Illuminate\Support\Facades\Route;

Route::get('_gildsmith/currencies', ReadCurrencies::class);
Route::get('_gildsmith/languages', ReadLanguages::class);

Route::get('_gildsmith/apps', ReadApplications::class);
Route::get('_gildsmith/apps/{app}', ReadApplications::class);
