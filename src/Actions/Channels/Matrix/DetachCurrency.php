<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Actions\Channels\Matrix;

use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Currency;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class DetachCurrency extends Action
{
    use AsController;

    public function asController(Channel $channel, Currency $currency): JsonResponse
    {
        return $this->handle($channel, $currency)
            ? response()->json($channel->refresh())
            : response()->json($channel, 403);
    }

    public function handle(Channel $channel, Currency $currency): bool
    {
        if ($channel->default_currency_id !== $currency->id) {
            $channel->currencies()->detach($currency->id);

            return true;
        }

        return false;
    }
}
