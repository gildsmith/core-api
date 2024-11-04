<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Controllers\Channels;

use Gildsmith\CoreApi\Http\Controllers\Controller;
use Gildsmith\CoreApi\Http\Requests\Channels\ChannelsCreateRequest;
use Gildsmith\CoreApi\Http\Resources\ChannelResource;
use Gildsmith\CoreApi\Models\Channel;

class ChannelsCreateController extends Controller
{
    public function __invoke(ChannelsCreateRequest $request)
    {
        $channel = Channel::create($request->validated());
        $channel->load($channel->with);

        return ChannelResource::make($channel);
    }
}
