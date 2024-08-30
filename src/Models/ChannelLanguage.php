<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $channel_id
 * @property int $language_id
 * @property Channel $channel
 * @property Language $language
 */
class ChannelLanguage extends Pivot
{
    use BroadcastsEvents;

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('gildsmith.dashboard.channels');
    }

    public function broadcastWith(): array
    {
        return [
            ...$this->language->toArray(),
            'pivot' => $this,
        ];
    }
}
