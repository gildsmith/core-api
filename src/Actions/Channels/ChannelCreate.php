<?php

namespace Gildsmith\HubApi\Actions\Channels;

use Gildsmith\HubApi\Models\Channel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsController;

class ChannelCreate extends Action
{
    use AsController;

    public function authorize(Request $request): bool
    {
        return (bool) $request->user() && $request->user()->role->name === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:1'],
        ];
    }

    public function asController(ActionRequest $request): JsonResponse
    {
        $channel = $this->handle($request->input('name'));

        return response()->json($channel);
    }

    public function handle(string $name): Channel
    {
        $channel = new Channel();
        $channel->name = $name;
        $channel->save();
        $channel->load($channel->with);

        return $channel;
    }
}
