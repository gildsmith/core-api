<?php

namespace Gildsmith\HubApi\Actions\Channels\Matrix;

use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Currency;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class AttachCurrency extends Action
{
    use AsController;

    public function rules(): array
    {
        return ['currency' => ['required', 'integer', 'exists:\Gildsmith\HubApi\Models\Currency,id']];
    }

    public function asController(ActionRequest $request, Channel $channel): JsonResponse
    {
        $currency = Currency::find($request->input('currency'));
        $this->handle($channel, $currency);

        return response()->json($channel);
    }

    public function handle(Channel $channel, Currency $currency): Channel
    {
        $channel->currencies()->attach($currency->id);
        $channel->refresh();

        return $channel;
    }
}
