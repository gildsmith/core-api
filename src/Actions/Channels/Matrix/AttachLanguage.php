<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Actions\Channels\Matrix;

use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Language;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class AttachLanguage extends Action
{
    use AsController;

    public function rules(): array
    {
        return ['language' => ['required', 'integer', 'exists:\Gildsmith\HubApi\Models\Language,id']];
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
