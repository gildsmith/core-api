<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions\Channels;

use Gildsmith\CoreApi\Models\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsController;

class ChannelsIndex extends Action
{
    use AsController;

    public function authorize(Request $request): bool
    {
        return $request->user() && $request->user()->role->name === 'admin';
    }

    public function asController(Request $request): JsonResponse
    {
        return response()->json($this->handle());
    }

    public function handle(): Collection
    {
        return Channel::all();
    }
}
