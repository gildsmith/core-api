<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions\Channels;

use Gildsmith\CoreApi\Models\Channel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class ChannelUpdate extends Action
{
    use AsController;

    public function authorize(Request $request): bool
    {
        return $request->user() && $request->user()->role->name === 'admin';
    }

    public function rules(): array
    {
        return [
            'maintenance' => ['boolean'],
            'default_currency_id' => ['integer', 'exists:\Gildsmith\CoreApi\Models\Currency,id'],
            'default_language_id' => ['integer', 'exists:\Gildsmith\CoreApi\Models\Language,id'],
        ];
    }

    public function asController(ActionRequest $request, Channel $channel): JsonResponse
    {
        $this->handle($channel, $request->validated());
        $channel->refresh();

        return response()->json($channel);
    }

    public function handle(Channel $channel, array $data): bool
    {
        $channel->fill($data);

        return $channel->save();
    }
}
