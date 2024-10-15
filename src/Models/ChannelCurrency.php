<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Models;

use Gildsmith\CoreApi\Exceptions\DefaultCurrencyDetachException;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $channel_id
 * @property int $currency_id
 * @property Channel $channel
 * @property Currency $currency
 */
class ChannelCurrency extends Pivot
{
    use BroadcastsEvents;

    protected static function booted(): void
    {
        static::deleting(function (ChannelCurrency $instance) {
            if ($instance->currency_id === $instance->channel->default_currency_id) {
                throw new DefaultCurrencyDetachException;
            }
        });
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class, 'channel_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('gildsmith.dashboard.channels');
    }

    public function broadcastWith(): array
    {
        return [
            ...$this->currency->toArray(),
            'pivot' => $this,
        ];
    }
}
