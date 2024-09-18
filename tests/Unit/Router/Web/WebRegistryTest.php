<?php

use Gildsmith\CoreApi\Router\Web\WebAppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;

beforeEach(function () {
    $reflectionClass = new ReflectionClass(WebRegistry::class);
    $registry = $reflectionClass->getProperty('registry');
    $registry->setValue(null, []);

    $fallback = $reflectionClass->getProperty('fallbackApplication');
    $fallback->setValue(null, new WebAppBuilder());
});

it('initializes with a fallback application', function () {
    WebRegistry::init();

    expect(WebRegistry::fallback())->toBeInstanceOf(WebAppBuilder::class);
});

it('adds a web application to the registry', function () {
    $app = new WebAppBuilder('home');
    WebRegistry::add($app);

    expect(WebRegistry::registry())->toContain($app);
});

it('returns the correct application when route matches', function () {
    $homeApp = new WebAppBuilder('home-app');
    $homeApp->route('home');

    $blogApp = new WebAppBuilder('blog-app');
    $blogApp->route('blog');

    WebRegistry::add($homeApp);
    WebRegistry::add($blogApp);

    expect(WebRegistry::get('blog'))->toBe($blogApp);
});

it('returns the fallback application when no matching route is found', function () {
    expect(WebRegistry::get('non-existent-route'))->toBe(WebRegistry::fallback());
});

it('returns the application matching only the first part of the route', function () {
    $app = new WebAppBuilder('blog');
    $app->route('blog');

    WebRegistry::add($app);

    expect(WebRegistry::get('blog/123/some-article'))->toBe($app);
});

it('returns the current fallback application', function () {
    $fallbackApp = new WebAppBuilder('fallback');
    WebRegistry::setFallback($fallbackApp);

    expect(WebRegistry::fallback())->toBe($fallbackApp);
});

it('sets a fallback application', function () {
    $fallbackApp = new WebAppBuilder('fallback');

    WebRegistry::setFallback($fallbackApp);

    expect(WebRegistry::fallback())->toBe($fallbackApp);
});

it('returns the current registry of applications', function () {
    $homeApp = new WebAppBuilder('home');
    $blogApp = new WebAppBuilder('blog');

    WebRegistry::add($homeApp);
    WebRegistry::add($blogApp);

    expect(WebRegistry::registry())->toBe([$homeApp, $blogApp]);
});

it('returns all registered applications including the fallback', function () {
    $app = new WebAppBuilder('home');
    WebRegistry::add($app);

    expect(WebRegistry::getFullRegistry())
        ->toContain($app)
        ->toContain(WebRegistry::fallback());
});
