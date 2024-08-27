<?php

use Gildsmith\HubApi\Actions\ReadApplications;
use Gildsmith\HubApi\Actions\ReadCurrencies;
use Gildsmith\HubApi\Actions\ReadLanguages;
use Illuminate\Support\Facades\Route;

Route::get('_gildsmith/currencies', ReadCurrencies::class);
Route::get('_gildsmith/languages', ReadLanguages::class);

Route::get('_gildsmith/apps', ReadApplications::class);
Route::get('_gildsmith/apps/{app}', ReadApplications::class);
