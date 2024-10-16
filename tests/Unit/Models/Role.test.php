<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Models\Role;
use Illuminate\Database\UniqueConstraintViolationException;

covers(Role::class);

describe('default roles', function () {

    it('has all default roles', function () {
        $allRoles = Role::all();
        $defaultRoles = Role::default();

        expect($allRoles)->toHaveCount($defaultRoles->count());
    });

    it('assigns ID 1 to user role and ID 2 to admin role', function () {
        expect(Role::find(1)->name)->toBe('user');
        expect(Role::find(2)->name)->toBe('admin');
    });

    it('ensures the role name is unique', function () {
        $firstRole = new Role();
        $firstRole->name = 'new role';
        $firstRole->save();

        $secondRole = new Role();
        $secondRole->name = 'new role';
        $secondRole->save();

    })->throws(UniqueConstraintViolationException::class);

});