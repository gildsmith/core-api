<?php

use Gildsmith\HubApi\Actions\ReadChannels;
use Illuminate\Support\Facades\Route;

Route::get('/', ReadChannels::class);