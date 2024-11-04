<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Gildsmith\CoreApi\Utils\ApplicationFilter;

covers(ApplicationFilter::class);

beforeEach(function () {
    $this->mockRegistry = mock(WebRegistry::class);

    $guestApp = mock(WebAppBuilder::class);
    $guestApp->allows('getGroups')->andReturn(['guest']);
    $guestApp->allows('getIdentifier')->andReturn('guestApp');

    $userApp = mock(WebAppBuilder::class);
    $userApp->allows('getGroups')->andReturn(['user']);
    $userApp->allows('getIdentifier')->andReturn('userApp');

    $adminApp = mock(WebAppBuilder::class);
    $adminApp->allows('getGroups')->andReturn(['admin']);
    $adminApp->allows('getIdentifier')->andReturn('adminApp');

    $multiRoleApp = mock(WebAppBuilder::class);
    $multiRoleApp->allows('getGroups')->andReturn(['guest', 'user']);
    $multiRoleApp->allows('getIdentifier')->andReturn('multiRoleApp');

    $apps = [$guestApp, $userApp, $adminApp, $multiRoleApp];

    $this->mockRegistry->allows('getFullRegistry')
        ->andReturn($apps);

})->afterEach(fn () => Mockery::close());

describe('apps filtering', function () {

    it('returns apps available for guest role by default', function () {
        $output = ApplicationFilter::filter($this->mockRegistry);
        $identifiers = collect($output)->map(fn ($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(2);
        expect($identifiers)->toContain('guestApp', 'multiRoleApp');
    });

    it('returns apps available for specified user role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'user');
        $identifiers = collect($output)->map(fn ($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(2);
        expect($identifiers)->toContain('userApp', 'multiRoleApp');
    });

    it('returns all apps for admin role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'admin');
        $identifiers = collect($output)->map(fn ($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(4);
        expect($identifiers)->toContain('guestApp', 'userApp', 'adminApp', 'multiRoleApp');
    });

    it('returns specific app if it is available for the given role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'user', 'multiRoleApp');
        $identifiers = collect($output)->map(fn ($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(1);
        expect($identifiers)->toContain('multiRoleApp');
    });

    it('returns an empty array if the specified app is not available for the given role', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'user', 'adminApp');
        $identifiers = collect($output)->map(fn ($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(0);
    });

    it('returns the requested app if admin role is specified, regardless of its groups', function () {
        $output = ApplicationFilter::filter($this->mockRegistry, 'admin', 'userApp');
        $identifiers = collect($output)->map(fn ($app) => $app->getIdentifier())->toArray();

        expect($identifiers)->toHaveCount(1);
        expect($identifiers)->toContain('userApp');
    });

})->afterEach(fn () => Mockery::close());
