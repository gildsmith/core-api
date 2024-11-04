<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Controllers\Channels;

use Gildsmith\CoreApi\Http\Controllers\Controller;
use Gildsmith\CoreApi\Http\Requests\Channels\ChannelsIndexRequest;
use Gildsmith\CoreApi\Models\Channel;

class ChannelsIndexController extends Controller
{
    public function __invoke(ChannelsIndexRequest $request)
    {
        return Channel::all();
    }
}
