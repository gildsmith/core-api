<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Router\Api\ApiFeature;

covers(ApiFeature::class);

/**
 * This test, while might looking silly, tests class
 * integrity in case its content is every modified.
 */
it('returns rule', function () {
    $ruleFunction = fn () => true;

    $apiFeature = new ApiFeature;
    $apiFeature->setRule($ruleFunction);

    expect($apiFeature->getRule())->toBe($ruleFunction);
});

it('returns name', function () {
    $apiFeature = new ApiFeature;
    $apiFeature->setName('first');
    $apiFeature->setName('second');

    expect($apiFeature->getName())->toBe('second');
});

it('returns pennant', function () {
    $apiFeature = new ApiFeature;

    expect($apiFeature->usesPennent())->toBeFalse();

    $apiFeature->shouldUsePennent();
    $apiFeature->shouldUsePennent();

    expect($apiFeature->usesPennent())->toBeTrue();
});

it('returns callables', function () {
    $callableOne = function () {
        return true;
    };
    $callableTwo = function () {
        return false;
    };

    $apiFeature = new ApiFeature;
    $apiFeature->addCallable($callableOne);
    $apiFeature->addCallable($callableTwo);

    expect($apiFeature->getCallables())->toBe([$callableOne, $callableTwo]);
});
