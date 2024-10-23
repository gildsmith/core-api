<?php

use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Gildsmith\CoreApi\Utils\ApplicationFilter;

covers(ApplicationFilter::class);

beforeEach(function () {
    $this->mockRegistry = Mockery::mock(WebRegistry::class);

    $guestApp = Mockery::mock(WebAppBuilder::class);
    $guestApp->shouldReceive('getGroups')->andReturn(['guest']);
    $guestApp->shouldReceive('getIdentifier')->andReturn('guestApp');

    $userApp = Mockery::mock(WebAppBuilder::class);
    $userApp->shouldReceive('getGroups')->andReturn(['user']);
    $userApp->shouldReceive('getIdentifier')->andReturn('userApp');

    $adminApp = Mockery::mock(WebAppBuilder::class);
    $adminApp->shouldReceive('getGroups')->andReturn(['admin']);
    $adminApp->shouldReceive('getIdentifier')->andReturn('adminApp');

    $multiRoleApp = Mockery::mock(WebAppBuilder::class);
    $multiRoleApp->shouldReceive('getGroups')->andReturn(['guest', 'user']);
    $multiRoleApp->shouldReceive('getIdentifier')->andReturn('multiRoleApp');

    $apps = [$guestApp, $userApp, $adminApp, $multiRoleApp];

    $this->mockRegistry->shouldReceive('getFullRegistry')
        ->andReturn($apps);

})->afterEach(fn() => Mockery::close());

describe('apps filtering', function () {

    it('returns apps available for guest role by default', function () {
        $output = ApplicationFilter::filter($this->mockRegistry);
        $identifiers = collect($output)->map(fn($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(2);
        expect($identifiers)->toContain('guestApp', 'multiRoleApp');
    });

    it('returns apps available for specified user role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'user');
        $identifiers = collect($output)->map(fn($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(2);
        expect($identifiers)->toContain('userApp', 'multiRoleApp');
    });

    it('returns all apps for admin role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'admin');
        $identifiers = collect($output)->map(fn($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(4);
        expect($identifiers)->toContain('guestApp', 'userApp', 'adminApp', 'multiRoleApp');
    });

    it('returns specific app if it is available for the given role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'user', 'multiRoleApp');
        $identifiers = collect($output)->map(fn($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(1);
        expect($identifiers)->toContain('multiRoleApp');
    });

    it('returns an empty array if the specified app is not available for the given role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'user', 'adminApp');
        $identifiers = collect($output)->map(fn($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(0);
    });

    it('returns the requested app if admin role is specified, regardless of its groups', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'admin', 'userApp');
        $identifiers = collect($output)->map(fn($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(1);
        expect($identifiers)->toContain('userApp');
    });

})->afterEach(fn() => Mockery::close());