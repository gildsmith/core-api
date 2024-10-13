<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Actions\Channels\ChannelCreate;
use Gildsmith\CoreApi\Actions\Channels\ChannelDelete;
use Gildsmith\CoreApi\Actions\Channels\ChannelIndex;
use Gildsmith\CoreApi\Actions\Channels\ChannelUpdate;
use Gildsmith\CoreApi\Actions\Channels\Pivot\AttachCurrency;
use Gildsmith\CoreApi\Actions\Channels\Pivot\AttachLanguage;
use Gildsmith\CoreApi\Actions\Channels\Pivot\DetachCurrency;
use Gildsmith\CoreApi\Actions\Channels\Pivot\DetachLanguage;
use Illuminate\Support\Facades\Route;

Route::get('/', ChannelIndex::class);
Route::post('/', ChannelCreate::class);
Route::delete('{channel}', ChannelDelete::class);
Route::patch('{channel}', ChannelUpdate::class);

Route::post('{channel}/languages', AttachLanguage::class);
Route::delete('{channel}/languages/{language}', DetachLanguage::class);

Route::post('{channel}/currencies', AttachCurrency::class);
Route::delete('{channel}/currencies/{currency}', DetachCurrency::class);
