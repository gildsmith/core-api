<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions\Channels;

use Gildsmith\CoreApi\Models\Channel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class ChannelDelete extends Action
{
    use AsController;

    public function authorize(Request $request): bool
    {
        return $request->user() && $request->user()->role->name === 'admin';
    }

    public function asController(Channel $channel): JsonResponse
    {
        $this->handle($channel);

        return response()->json(null, 204);
    }

    public function handle(Channel $channel): bool
    {
        return $channel->delete();
    }
}
