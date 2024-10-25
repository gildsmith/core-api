<?php

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Enums\UserRoleEnum;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ReadApplications;
use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;

covers(ReadApplications::class);

beforeEach(function () {
    $this->mockRegistry = Mockery::mock(WebRegistry::class);

    $guestApp = Mockery::mock(WebAppBuilder::class);
    $guestApp->shouldReceive('getGroups')->andReturn(['guest']);
    $guestApp->shouldReceive('getIdentifier')->andReturn('guestApp');
    $guestApp->shouldReceive('jsonSerialize')->andReturn([
        'identifier' => 'guestApp',
        'groups' => ['guest']
    ]);

    $userApp = Mockery::mock(WebAppBuilder::class);
    $userApp->shouldReceive('getGroups')->andReturn(['user']);
    $userApp->shouldReceive('getIdentifier')->andReturn('userApp');
    $userApp->shouldReceive('jsonSerialize')->andReturn([
        'identifier' => 'userApp',
        'groups' => ['user']
    ]);

    $adminApp = Mockery::mock(WebAppBuilder::class);
    $adminApp->shouldReceive('getGroups')->andReturn(['admin']);
    $adminApp->shouldReceive('getIdentifier')->andReturn('adminApp');
    $adminApp->shouldReceive('jsonSerialize')->andReturn([
        'identifier' => 'adminApp',
        'groups' => ['admin']
    ]);

    $multiRoleApp = Mockery::mock(WebAppBuilder::class);
    $multiRoleApp->shouldReceive('getGroups')->andReturn(['guest', 'user']);
    $multiRoleApp->shouldReceive('getIdentifier')->andReturn('multiRoleApp');
    $multiRoleApp->shouldReceive('jsonSerialize')->andReturn([
        'identifier' => 'multiRoleApp',
        'groups' => ['guest', 'user']
    ]);

    $this->mockRegistry
        ->shouldReceive('getFullRegistry')
        ->andReturn([$guestApp, $userApp, $adminApp, $multiRoleApp]);

    $this->app->instance(WebRegistry::class, $this->mockRegistry);

})->afterEach(fn() => Mockery::close());

describe('controller access', function () {

    it('user can only see respective apps', function () {
        $user = (new UserFactory())->create(['role_id' => UserRoleEnum::USER->id()]);
        $response = $this->actingAs($user)->get('api/gildsmith/apps');

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toContain('userApp', 'multiRoleApp');
        expect($identifiers)->not->toContain('adminApp');
    });

    it('guest (unauthenticated user) can only see public apps', function () {
        $response = $this->get('api/gildsmith/apps');

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toContain('guestApp', 'multiRoleApp');
        expect($identifiers)->not->toContain('userApp', 'adminApp');
    });

    it('admin can see all apps', function () {
        $adminUser = (new UserFactory())->admin()->create();
        $response = $this->actingAs($adminUser)->get('api/gildsmith/apps');

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toContain('guestApp', 'userApp', 'adminApp', 'multiRoleApp');
    });

    it('user cannot see selected app if not allowed', function () {
        $user = (new UserFactory())->create(['role_id' => UserRoleEnum::USER->id()]);
        $response = $this->actingAs($user)->get('api/gildsmith/apps/adminApp');

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toBeEmpty();
    });

    it('guest (unauthenticated user) cannot see selected app if not allowed', function () {
        $response = $this->get('api/gildsmith/apps/userApp');

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toBeEmpty();
    });

    it('admin can see any selected app, even without explicit permissions', function () {
        $adminUser = (new UserFactory())->admin()->create();
        $response = $this->actingAs($adminUser)->get('api/gildsmith/apps/guestApp');

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toContain('guestApp');
    });

});