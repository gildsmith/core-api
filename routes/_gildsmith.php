<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Actions\ReadApplications;
use Gildsmith\CoreApi\Actions\ReadCurrencies;
use Gildsmith\CoreApi\Actions\ReadLanguages;
use Illuminate\Support\Facades\Route;

Route::get('_gildsmith/currencies', ReadCurrencies::class);
Route::get('_gildsmith/languages', ReadLanguages::class);

Route::get('_gildsmith/apps', ReadApplications::class);
Route::get('_gildsmith/apps/{app}', ReadApplications::class);
