<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Exceptions\DefaultCurrencyDetachException;
use Gildsmith\CoreApi\Exceptions\DefaultLanguageDetachException;
use Gildsmith\CoreApi\Models\Channel;
use Illuminate\Database\QueryException;

covers(Channel::class);

/*
 * Tests related to the default channel (ID 1), focusing
 * on its preexistence and ability to be automatically
 * recreated after deletion.
 *
 * Note: These tests use the 'deleted' event, which may
 * not trigger in all cases. For example, truncating
 * the entire 'channels' table with Channel::truncate()
 * will not fire the event.
 */
describe('default channel', function () {

    it('exists by default', function () {
        $this->assertDatabaseHas('channels', ['id' => 1]);
    });

    it('is immediately recreated after deletion', function () {
        Channel::find(1)->delete();

        $channels = Channel::all();
        expect($channels)->toHaveCount(1);
        expect($channels->first()->id)->toBe(1);
    });
});

/*
 * Tests related to default currency and language behavior,
 * ensuring proper defaults, relationship constraints, and
 * valid operations when handling currencies and languages
 * within a channel.
 */
describe('default currency and language', function () {

    it('has initial default values', function () {
        $channel = new Channel;
        $channel->name = 'Test Channel';
        $channel->save();

        expect($channel->default_currency_id)->not->toBeEmpty();
        expect($channel->default_language_id)->not->toBeEmpty();

        expect($channel->currencies)->toHaveCount(1);
        expect($channel->languages)->toHaveCount(1);
    });

    it('does not allow default values to be set outside of the channel relations', function () {
        $channel = new Channel;
        $channel->name = 'Test Channel';
        $channel->save();

        $channel->currencies()->attach(10);
        $channel->languages()->attach(10);

        $channel->refresh();

        expect($channel->default_currency_id)->toBe(Channel::first()->default_currency_id);
        expect($channel->default_language_id)->toBe(Channel::first()->default_language_id);
    });

    it('allows default values to be changed to values from relations', function () {
        $channel = new Channel;
        $channel->name = 'Test Channel';
        $channel->save();

        $channel->currencies()->attach(10);
        $channel->languages()->attach(10);

        $channel->default_currency_id = 10;
        $channel->default_language_id = 10;
        $channel->save();

        $channel->refresh();

        expect($channel->default_currency_id)->toBe(10);
        expect($channel->default_language_id)->toBe(10);
    });

    it('does not allow default values to be set to non-existent IDs', function () {
        $channel = new Channel;
        $channel->name = 'Test Channel';
        $channel->save();

        $channel->currencies()->attach(PHP_INT_MAX);
        $channel->languages()->attach(PHP_INT_MAX);

    })->throws(QueryException::class);

});

/*
 * Tests related to channel relations, ensuring that the
 * proper constraints and behavior are enforced. These
 * tests validate relationships, enforce limits, and check
 * unique constraints for related entities.
 */
describe('channel relations', function () {

    it('ensures at least one currency and language is present', function () {
        $channel = new Channel;
        $channel->name = 'Test Channel';
        $channel->save();

        $channel->currencies()->detach($channel->default_currency_id);
        $channel->languages()->detach($channel->default_language_id);

        $channel->refresh();

        expect($channel->currencies)->toHaveCount(1);
        expect($channel->languages)->toHaveCount(1);

    })->throws(DefaultLanguageDetachException::class)
        ->throws(DefaultCurrencyDetachException::class);

    it('allows unlimited currencies and languages', function () {
        //
    });

    it('ensures relationships are unique', function () {
        //
    });

    it('loads the object with all relations', function () {
        //
    });

});

/*
 * TODO
 */
describe('broadcast events', function () {
    // - Broadcast on gildsmith.dashboard.channels is successful.
    // - Model is passed.
    // - Event type is passed.
});
