<?php

namespace Gildsmith\HubApi\Events;

use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Currency;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CurrencyAttached implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public Channel $channel;
    public Currency $currency;

    public function __construct(Channel $channel, Currency $currency)
    {
        $this->currency = $currency;
        $this->channel = $channel;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('dashboard');
    }
}