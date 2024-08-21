<?php

/** @noinspection PhpUnused */

namespace Gildsmith\HubApi\Models;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $default_currency_id
 * @property int $default_language_id
 * @property bool $maintenace
 * @property bool $locked
 */
class Channel extends Model
{
    use BroadcastsEvents;

    public $timestamps = false;

    public $with = ['defaultCurrency', 'defaultLanguage', 'currencies', 'languages'];

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (Channel $channel) {
            /* ID 154 should match United States Dollars */
            if (! $channel->default_currency_id) {
                $channel->default_currency_id = 154;
            }

            /* ID 37 should match English */
            if (! $channel->default_language_id) {
                $channel->default_language_id = 37;
            }
        });

        static::created(function (Channel $channel) {
            $channel->currencies()->attach($channel->default_currency_id);
            $channel->languages()->attach($channel->default_language_id);
        });

        static::deleted(function (Channel $channel) {
            if ($channel->id === 1) {
                $channel = self::defaultBlueprint();
                $channel->save();
            }
        });
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class)->using(ChannelCurrency::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class)->using(ChannelLanguage::class);
    }

    public static function defaultBlueprint(): self
    {
        $channel = new Channel();
        $channel->id = 1;
        $channel->name = 'Default channel';
        $channel->description = 'This channel is the default and cannot be deleted. When pruned, it will be instantly recreated with the ID 1.';

        return $channel;
    }

    public function defaultCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function defaultLanguage(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('gildsmith.dashboard.channels');
    }

    public function broadcastWith(): array
    {
        return $this->toArray();
    }
}
