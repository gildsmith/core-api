<?php

use Gildsmith\CoreApi\Database\Factories\UserFactory;
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


describe('controller features', function () {

    it('returns apps in correct format for admin user', function () {
        $adminUser = (new UserFactory())->admin()->create();
        $response = $this->actingAs($adminUser)->get('api/gildsmith/apps');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'identifier',
                'groups',
            ],
        ]);

        $apps = json_decode($response->getContent(), true);
        $identifiers = collect($apps)->pluck('identifier')->toArray();

        expect($identifiers)->toContain('guestApp', 'userApp', 'adminApp', 'multiRoleApp');
    });

});