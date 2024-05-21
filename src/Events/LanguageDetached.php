<?php

namespace Gildsmith\HubApi\Events;

use Gildsmith\HubApi\Models\Channel;
use Gildsmith\HubApi\Models\Language;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LanguageDetached implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public Channel $channel;
    public Language $language;

    public function __construct(Channel $channel, Language $language)
    {
        $this->language = $language;
        $this->channel = $channel;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('dashboard');
    }
}