<?php

namespace Gildsmith\HubApi\Actions\Channels;

use Gildsmith\HubApi\Events\LanguageDetached;
use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Language;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class DetachLanguageFromChannel extends Action
{
    use AsController;

    public function asController(Channel $channel, Language $language): JsonResponse
    {
        $this->handle($channel, $language);
        return response()->json($channel);
    }

    public function handle(Channel $channel, Language $language): Channel
    {
        $channel->languages()->detach($language->id);
        $channel->load('currencies', 'languages');

        LanguageDetached::dispatch($channel, $language);

        return $channel;
    }
}