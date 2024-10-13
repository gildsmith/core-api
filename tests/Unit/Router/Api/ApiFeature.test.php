<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Router\Api\ApiFeature;

it('initializes with default values', function () {
    $feature = new ApiFeature;

    expect($feature->getRule())
        ->toBeNull();

    expect($feature->getName())
        ->toBeNull();

    expect($feature->getCallables())
        ->toBeArray()
        ->toBeEmpty();

    expect($feature->usesPennent())
        ->toBeFalse();
});

it('sets and gets the feature rule', function () {
    $feature = new ApiFeature;

    $rule = fn () => true;
    $feature->setRule($rule);

    expect($feature->getRule())
        ->toBe($rule);
});

it('sets and gets the feature name', function () {
    $feature = new ApiFeature;

    $name = 'api-feature';
    $feature->setName($name);

    expect($feature->getName())
        ->toBe($name);
});

it('adds and retrieves callables', function () {
    $feature = new ApiFeature;

    $callable1 = fn () => 'route1';
    $callable2 = fn () => 'route2';

    $feature->addCallable($callable1);
    $feature->addCallable($callable2);

    expect($feature->getCallables())
        ->toBeArray()
        ->toHaveCount(2)
        ->toContain($callable1, $callable2);
});

it('enables pennent feature', function () {
    $feature = new ApiFeature;

    expect($feature->usesPennent())
        ->toBeFalse();

    $feature->shouldUsePennent();

    expect($feature->usesPennent())
        ->toBeTrue();
});
