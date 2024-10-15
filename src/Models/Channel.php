<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Models;

use Gildsmith\CoreApi\Database\Factories\ChannelFactory;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property Collection $currencies
 * @property Collection $languages
 * @property int $default_currency_id
 * @property int $default_language_id
 * @property Currency $defaultCurrency
 * @property Language $defaultLanguage
 * @property bool $maintenance
 */
class Channel extends Model
{
    use BroadcastsEvents, HasFactory;

    public $timestamps = false;

    public $with = ['defaultCurrency', 'defaultLanguage', 'currencies', 'languages'];

    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function (Channel $channel) {
            /* ID 154 should match United States Dollars */
            if (! $channel->default_currency_id) {
                $channel->default_currency_id = self::defaultBlueprint()->default_currency_id;
            }

            /* ID 37 should match English */
            if (! $channel->default_language_id) {
                $channel->default_language_id = self::defaultBlueprint()->default_language_id;
            }
        });

        static::created(function (Channel $channel) {
            $channel->currencies()->attach($channel->default_currency_id);
            $channel->languages()->attach($channel->default_language_id);
        });

        static::deleted(function (Channel $channel) {
            if ($channel->id === self::defaultBlueprint()->id) {
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
        $channel = new Channel;
        $channel->id = 1;
        $channel->name = 'Default channel';
        $channel->description = 'This channel is the default and cannot be deleted. When pruned, it will be instantly recreated with the ID 1.';
        $channel->default_currency_id = 154;
        $channel->default_language_id = 37;

        return $channel;
    }

    protected static function newFactory(): ChannelFactory
    {
        return ChannelFactory::new();
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

    public function broadcastWith(string $event): array
    {
        return $this->toArray();
    }
}
