<?php

namespace Gildsmith\HubApi\Actions\Channels;

use Gildsmith\HubApi\Events\CurrencyAttached;
use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Currency;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class AttachCurrencyToChannel extends Action
{
    use AsController;

    public function asController(Channel $channel, Currency $currency): JsonResponse
    {
        $this->handle($channel, $currency);
        return response()->json($channel);
    }

    public function handle(Channel $channel, Currency $currency): Channel
    {
        $channel->currencies()->attach($currency->id);
        $channel->load('currencies', 'languages');

        CurrencyAttached::dispatch($channel, $currency);

        return $channel;
    }
}