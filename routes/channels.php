<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Http\Controllers\Channels\ChannelsCreateController;
use Gildsmith\CoreApi\Http\Controllers\Channels\ChannelsIndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', ChannelsIndexController::class);
Route::post('/', ChannelsCreateController::class);
