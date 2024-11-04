<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Database\Factories\UserFactory;
use Gildsmith\CoreApi\Enums\UserRoleEnum;
use Gildsmith\CoreApi\Http\Controllers\Gildsmith\ApplicationsIndexController;
use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;

covers(ApplicationsIndexController::class);

beforeEach(function () {
    $this->mockRegistry = mock(WebRegistry::class);

    $guestApp = mock(WebAppBuilder::class);
    $guestApp->allows('getGroups')->andReturns(['guest']);
    $guestApp->allows('getIdentifier')->andReturn('guestApp');
    $guestApp->allows('jsonSerialize')->andReturn([
        'identifier' => 'guestApp',
        'groups' => ['guest'],
    ]);

    $userApp = mock(WebAppBuilder::class);
    $userApp->allows('getGroups')->andReturn(['user']);
    $userApp->allows('getIdentifier')->andReturn('userApp');
    $userApp->allows('jsonSerialize')->andReturn([
        'identifier' => 'userApp',
        'groups' => ['user'],
    ]);

    $adminApp = mock(WebAppBuilder::class);
    $adminApp->allows('getGroups')->andReturn(['admin']);
    $adminApp->allows('getIdentifier')->andReturn('adminApp');
    $adminApp->allows('jsonSerialize')->andReturn([
        'identifier' => 'adminApp',
        'groups' => ['admin'],
    ]);

    $multiRoleApp = mock(WebAppBuilder::class);
    $multiRoleApp->allows('getGroups')->andReturn(['guest', 'user']);
    $multiRoleApp->allows('getIdentifier')->andReturn('multiRoleApp');
    $multiRoleApp->allows('jsonSerialize')->andReturn([
        'identifier' => 'multiRoleApp',
        'groups' => ['guest', 'user'],
    ]);

    $this->mockRegistry
        ->allows('getFullRegistry')
        ->andReturn([$guestApp, $userApp, $adminApp, $multiRoleApp]);

    $this->app->instance(WebRegistry::class, $this->mockRegistry);

})->afterEach(fn () => Mockery::close());

it('user can only see respective apps', function () {
    $user = (new UserFactory)->create(['role_id' => UserRoleEnum::USER->id()]);
    $response = $this->actingAs($user)->get('api/gildsmith/apps');

    $apps = json_decode($response->getContent(), true);
    $identifiers = collect($apps)->pluck('identifier')->toArray();

    expect($identifiers)->toContain('userApp', 'multiRoleApp');
    expect($identifiers)->not->toContain('adminApp');
});

it('guest can only see public apps', function () {
    $response = $this->get('api/gildsmith/apps');

    $apps = json_decode($response->getContent(), true);
    $identifiers = collect($apps)->pluck('identifier')->toArray();

    expect($identifiers)->toContain('guestApp', 'multiRoleApp');
    expect($identifiers)->not->toContain('userApp', 'adminApp');
});

it('admin can see all apps', function () {
    $adminUser = (new UserFactory)->admin()->create();
    $response = $this->actingAs($adminUser)->get('api/gildsmith/apps');

    $apps = json_decode($response->getContent(), true);
    $identifiers = collect($apps)->pluck('identifier')->toArray();

    expect($identifiers)->toContain('guestApp', 'userApp', 'adminApp', 'multiRoleApp');
});

it('user cannot see selected app if not allowed', function () {
    $user = (new UserFactory)->create(['role_id' => UserRoleEnum::USER->id()]);
    $response = $this->actingAs($user)->get('api/gildsmith/apps/adminApp');

    $apps = json_decode($response->getContent(), true);
    $identifiers = collect($apps)->pluck('identifier')->toArray();

    expect($identifiers)->toBeEmpty();
});

it('guest cannot see selected app if not allowed', function () {
    $response = $this->get('api/gildsmith/apps/userApp');

    $apps = json_decode($response->getContent(), true);
    $identifiers = collect($apps)->pluck('identifier')->toArray();

    expect($identifiers)->toBeEmpty();
});

it('admin can see any selected app, even without explicit permissions', function () {
    $adminUser = (new UserFactory)->admin()->create();
    $response = $this->actingAs($adminUser)->get('api/gildsmith/apps/guestApp');

    $apps = json_decode($response->getContent(), true);
    $identifiers = collect($apps)->pluck('identifier')->toArray();

    expect($identifiers)->toContain('guestApp');
});
