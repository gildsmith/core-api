<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions\Channels\Pivot;

use Gildsmith\CoreApi\Models\Channel;
use Gildsmith\CoreApi\Models\Language;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class AttachLanguage extends Action
{
    use AsController;

    public function rules(): array
    {
        return ['language' => ['required', 'integer', 'exists:\Gildsmith\CoreApi\Models\Language,id']];
    }

    public function asController(ActionRequest $request, Channel $channel): JsonResponse
    {
        $language = Language::find($request->input('language'));
        $this->handle($channel, $language);

        return response()->json($channel);
    }

    public function handle(Channel $channel, Language $language): Channel
    {
        $channel->languages()->attach($language->id);
        $channel->refresh();

        return $channel;
    }
}
