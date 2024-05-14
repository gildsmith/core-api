<?php

namespace Gildsmith\HubApi\Actions;

use Gildsmith\HubApi\Models\Channel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;

class ReadChannels extends Action
{
    use AsController, AsCommand;

    public function authorize(Request $request): bool
    {
        return !!$request->user() && $request->user()->role->name === 'admin';
    }

    public function handle(): Collection
    {
        return Channel::with(['currencies', 'languages'])->get();
    }

    public function asController(Request $request): JsonResponse
    {
        return response()->json($this->handle());
    }
}