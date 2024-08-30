<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions\Channels\Pivot;

use Gildsmith\CoreApi\Models\Channel;
use Gildsmith\CoreApi\Models\Language;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class DetachLanguage extends Action
{
    use AsController;

    public function asController(Channel $channel, Language $language): JsonResponse
    {
        return $this->handle($channel, $language)
            ? response()->json($channel->refresh())
            : response()->json($channel, 403);
    }

    public function handle(Channel $channel, Language $language): bool
    {
        if ($channel->default_language_id !== $language->id) {
            $channel->languages()->detach($language->id);

            return true;
        }

        return false;
    }
}
