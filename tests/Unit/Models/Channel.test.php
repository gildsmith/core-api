<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Models\Channel;

covers(Channel::class);

/*
 * Tests related to the default channel (ID 1), focusing on its
 * preexistence and ability to be automatically recreated after deletion.
 *
 * Note: These tests use the 'deleted' event, which may not trigger
 * in all cases. For example, truncating the entire 'channels' table
 * with Channel::truncate() will not fire the event.
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
 * TODO
 */
describe('default currency and language', function () {

    it('has initial default values', function () {
        //
    });

    it('always has default values', function () {
        //
    });

    it('allows default values to be changed to values from relations', function () {
        //
    });

    it('does not allow default values to be set outside of the channel relations', function () {
        //
    });

    it('does not allow default values to be set to non-existent IDs', function () {
        //
    });

});

/*
 * TODO
 */
describe('channel relations', function () {

    it('ensures at least one currency and language is present', function () {
        //
    });

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
