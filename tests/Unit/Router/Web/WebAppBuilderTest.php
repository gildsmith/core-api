<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Router\Web\WebAppBuilder;

it('sets the default identifier when no argument is passed to the constructor', function () {
    $app = new WebAppBuilder();
    expect($app->getIdentifier())->toBe('storefront');
});

it('sets the identifier when an argument is passed to the constructor', function () {
    $app = new WebAppBuilder('custom-app');
    expect($app->getIdentifier())->toBe('custom-app');
});

it('returns the default template', function () {
    $app = new WebAppBuilder();
    expect($app->getTemplate())->toBe('gildsmith.template');
});

it('allows setting a custom template', function () {
    $app = new WebAppBuilder();
    $app->template('custom.template');
    expect($app->getTemplate())->toBe('custom.template');
});

it('returns the default route', function () {
    $app = new WebAppBuilder();
    expect($app->getRoute())->toBe('');
});

it('allows setting a custom route', function () {
    $app = new WebAppBuilder();
    $app->route('custom');
    expect($app->getRoute())->toBe('custom');
});

it('initially returns an empty array for groups', function () {
    $app = new WebAppBuilder();
    expect($app->getGroups())->toBe([]);
});

it('allows adding a group via setGroups', function () {
    $app = new WebAppBuilder();
    $app->setGroups('admin');
    expect($app->getGroups())->toContain('admin');
});

it('allows adding multiple groups via setGroups', function () {
    $app = new WebAppBuilder();
    $app->setGroups('admin', 'editor');
    expect($app->getGroups())->toContain('admin', 'editor');
});

it('allows adding groups via restrictedTo method', function () {
    $app = new WebAppBuilder();
    $app->restrictedTo('admin', 'editor');
    expect($app->getGroups())->toContain('admin', 'editor');
});

it('initially returns an empty array for params', function () {
    $app = new WebAppBuilder();
    expect($app->getParams())->toBe([]);
});

it('allows setting parameters via param method', function () {
    $app = new WebAppBuilder();
    $app->param('key', 'value');
    expect($app->getParams())->toBe(['key' => 'value']);
});

it('allows setting multiple parameters via param method', function () {
    $app = new WebAppBuilder();
    $app->param('key1', 'value1')->param('key2', 'value2');
    expect($app->getParams())->toBe([
        'key1' => 'value1',
        'key2' => 'value2'
    ]);
});