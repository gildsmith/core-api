<?php

use Gildsmith\HubApi\Actions\Channels\AttachCurrencyToChannel;
use Gildsmith\HubApi\Actions\Channels\AttachLanguageToChannel;
use Gildsmith\HubApi\Actions\Channels\DetachCurrencyFromChannel;
use Gildsmith\HubApi\Actions\Channels\DetachLanguageFromChannel;
use Gildsmith\HubApi\Actions\ReadChannels;
use Illuminate\Support\Facades\Route;

Route::get('/', ReadChannels::class);

Route::post('channel/{channel}/language/{language}', AttachLanguageToChannel::class);
Route::delete('channel/{channel}/language/{language}', DetachLanguageFromChannel::class);

Route::post('channel/{channel}/currency/{currency}', AttachCurrencyToChannel::class);
Route::delete('channel/{channel}/currency/{currency}', DetachCurrencyFromChannel::class);