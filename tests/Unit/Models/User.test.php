<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Enums\UserRoleEnum;
use Gildsmith\CoreApi\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;

covers(User::class);

/*
 * Tests for user roles, ensuring that every new user
 * is assigned a default role and the admin role is never
 * assigned unintentionally.
 */
describe('role property', function () {

    it('has default role', function () {
        $user = (new UserFactory)->create();

        expect($user->role_id)->toBe(UserRoleEnum::USER->id());
        expect($user->role)->not->toBeNull();
    });

    it('can be modified', function () {
        $user = (new UserFactory)->create();
        $user->role_id = UserRoleEnum::ADMIN->id();
        $user->save();

        expect($user->role->id)->toBe(UserRoleEnum::ADMIN->id());
    });

    it('cannot be unset', function () {
        $user = (new UserFactory)->create();
        $user->role_id = null;
        $user->save();

    })->throws(QueryException::class);

});

/*
 * These tests check basic password safety, including
 * its hashing and protection from being printed.
 */
describe('password property', function () {

    it('is hashed', function () {
        $user = new User;
        $user->email = 'example@mail.com';
        $user->password = 'password';
        $user->save();

        expect($user->password)->not->toBe('password');
    });

    it('is hidden from output', function () {
        $user = (new UserFactory)->create();

        expect($user->toArray())->not->toHaveKey('password');
    });

});

/*
 * These simple tests make sure that functionality regarding
 * emails and validation work properly.
 */
describe('email property', function () {

    it('must be unique', function () {
        (new UserFactory)->create(['email' => 'example@mail.com']);
        (new UserFactory)->create(['email' => 'example@mail.com']);

    })->throws(UniqueConstraintViolationException::class);

    it('email_verified_at is cast to datetime', function () {
        $user = (new UserFactory)->create();

        expect($user->email_verified_at)->toBeInstanceOf(DateTime::class);
    });

});

describe('notifications', function () {
    // TODO
});
