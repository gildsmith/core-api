<?php

declare(strict_types=1);

use Gildsmith\HubApi\Actions\Channels\ChannelCreate;
use Gildsmith\HubApi\Actions\Channels\ChannelDelete;
use Gildsmith\HubApi\Actions\Channels\ChannelsIndex;
use Gildsmith\HubApi\Actions\Channels\ChannelUpdate;
use Gildsmith\HubApi\Actions\Channels\Matrix\AttachCurrency;
use Gildsmith\HubApi\Actions\Channels\Matrix\AttachLanguage;
use Gildsmith\HubApi\Actions\Channels\Matrix\DetachCurrency;
use Gildsmith\HubApi\Actions\Channels\Matrix\DetachLanguage;
use Illuminate\Support\Facades\Route;

Route::get('/', ChannelsIndex::class);
Route::post('/', ChannelCreate::class);
Route::delete('{channel}', ChannelDelete::class);
Route::patch('{channel}', ChannelUpdate::class);

Route::post('{channel}/languages', AttachLanguage::class);
Route::delete('{channel}/languages/{language}', DetachLanguage::class);

Route::post('{channel}/currencies', AttachCurrency::class);
Route::delete('{channel}/currencies/{currency}', DetachCurrency::class);
