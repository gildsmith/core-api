<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ApplicationsIndexController;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\CurrenciesIndexController;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\LanguagesIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/currencies', CurrenciesIndexController::class);
Route::get('/languages', LanguagesIndexController::class);

Route::get('/apps', ApplicationsIndexController::class);
Route::get('/apps/{app}', ApplicationsIndexController::class);
