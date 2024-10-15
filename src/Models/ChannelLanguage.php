<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Models;

use Gildsmith\CoreApi\Exceptions\DefaultLanguageDetachException;
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

    protected static function booted(): void
    {
        static::deleting(function (ChannelLanguage $instance) {
            if ($instance->language_id === $instance->channel->default_language_id) {
                throw new DefaultLanguageDetachException;
            }
        });
    }

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
