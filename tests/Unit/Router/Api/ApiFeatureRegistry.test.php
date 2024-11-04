<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Router\Api\ApiFeatureBuilder;
use Gildsmith\CoreApi\Router\Api\ApiFeatureRegistry;

covers(ApiFeatureRegistry::class);

beforeEach(function () {
    $reflector = new ReflectionClass(ApiFeatureRegistry::class);

    /** @noinspection PhpUnhandledExceptionInspection */
    $reflector->getProperty('registry')->setValue([]);
});

describe('scope', function () {
    it('allows adding different features with different identifiers', function () {
        $mockBuilder1 = mock(ApiFeatureBuilder::class);
        $mockBuilder1->allows('build');

        $mockBuilder2 = mock(ApiFeatureBuilder::class);
        $mockBuilder2->allows('build');

        ApiFeatureRegistry::add('first-feature', $mockBuilder1);
        ApiFeatureRegistry::add('second-feature', $mockBuilder2);

        $reflector = new ReflectionClass(ApiFeatureRegistry::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        expect($reflector->getProperty('registry')->getValue())->toEqual([
            'first-feature' => [$mockBuilder1],
            'second-feature' => [$mockBuilder2],
        ]);
    });

    it('allows registering multiple features under the same identifier', function () {
        $mockBuilder1 = mock(ApiFeatureBuilder::class);
        $mockBuilder1->allows('build');

        $mockBuilder2 = mock(ApiFeatureBuilder::class);
        $mockBuilder2->allows('build');

        ApiFeatureRegistry::add('first-feature', $mockBuilder1);
        ApiFeatureRegistry::add('first-feature', $mockBuilder2);

        $reflector = new ReflectionClass(ApiFeatureRegistry::class);

        /** @noinspection PhpUnhandledExceptionInspection */
        expect($reflector->getProperty('registry')->getValue())->toEqual([
            'first-feature' => [$mockBuilder1, $mockBuilder2],
        ]);
    });

    it('calls all registered features', function () {
        $mockBuilder1 = mock(ApiFeatureBuilder::class);
        $mockBuilder1->allows('build')->once();

        $mockBuilder2 = mock(ApiFeatureBuilder::class);
        $mockBuilder2->allows('build')->once();

        $mockBuilder3 = mock(ApiFeatureBuilder::class);
        $mockBuilder3->allows('build')->once();

        ApiFeatureRegistry::add('first-feature', $mockBuilder1);
        ApiFeatureRegistry::add('second-feature', $mockBuilder2);
        ApiFeatureRegistry::add('second-feature', $mockBuilder3);
        ApiFeatureRegistry::add('second-feature', $mockBuilder3);

        ApiFeatureRegistry::call();
    });
});
