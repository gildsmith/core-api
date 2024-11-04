<?php

declare(strict_types=1);

use Gildsmith\CoreApi\Models\User;
use Gildsmith\CoreApi\Router\Api\ApiFeature;
use Gildsmith\CoreApi\Router\Api\ApiFeatureBuilder;
use Illuminate\Routing\RouteRegistrar;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

covers(ApiFeatureBuilder::class);

beforeEach(function () {
    /** @var ApiFeature $mock */
    $mock = mock(ApiFeature::class)->makePartial();

    $this->builder = new ApiFeatureBuilder('test', $mock);
    $this->mockFeature = $mock;

})->afterEach(fn () => Mockery::close());

describe('file method', function () {

    it('adds a callable to a feature based on required file', function () {
        $file = __DIR__.'/temp-dummy.php';

        file_put_contents($file, '');

        $this->builder->file($file);
        $this->mockFeature->shouldHaveReceived('addCallable')->once();

        unlink($file);
    });

});

describe('callable method', function () {

    it('adds a callable to a feature', function () {
        $callable = fn () => true;

        $this->builder->callable($callable);

        $this->mockFeature
            ->shouldHaveReceived('addCallable')
            ->with($callable)->once();
    });

});

describe('route callable building', function () {

    it('defines feature when usesPennent is on', function () {
        $this->mockFeature->allows('usesPennent')->andReturnTrue();
        $this->mockFeature->allows('getName')->andReturn('test');
        $this->mockFeature->allows('getRule')->andReturn(function () {});

        Feature::shouldReceive('define')
            ->with('test', Mockery::type('callable'))
            ->once();

        $registrarMock = mock(RouteRegistrar::class);

        Route::shouldReceive('prefix')
            ->with('test')
            ->andReturn($registrarMock)
            ->once();

        $registrarMock->allows('middleware')
            ->with(EnsureFeaturesAreActive::using('test'))
            ->andReturnSelf()
            ->once();

        $registrarMock->allows('group')
            ->andReturnSelf()
            ->once();

        $callable = $this->builder->build();
        $callable();
    });

    it('defines feature when usesPennent is off', function () {
        $this->mockFeature->allows('usesPennent')->andReturnFalse();
        $this->mockFeature->allows('getName')->andReturn('test');
        $this->mockFeature->allows('getRule')->andReturn(function () {});

        Feature::shouldReceive('define')->never();

        $registrarMock = mock(RouteRegistrar::class);

        Route::shouldReceive('prefix')->with('test')
            ->andReturn($registrarMock)->once();

        $registrarMock->allows('middleware')->never();

        $registrarMock->allows('group')
            ->andReturnSelf()->once();

        $callable = $this->builder->build();
        $callable();
    });

});

describe('flagged method', function () {

    it('allows callables', function () {
        $rule = fn (User $user) => $user->hasRole('admin');
        $this->builder->flagged($rule);

        $this->mockFeature->shouldHaveReceived('shouldUsePennent')->once();
        $this->mockFeature->shouldHaveReceived('setRule')->with($rule)->once();
    });

    it('allows arrays', function () {
        $this->builder->flagged(['admin', 'editor']);

        $this->mockFeature->shouldHaveReceived('shouldUsePennent')->once();
        $this->mockFeature->shouldHaveReceived('setRule')->once();
    });

    it('allows strings', function () {
        $this->builder->flagged('admin');

        $this->mockFeature->shouldHaveReceived('shouldUsePennent')->once();
        $this->mockFeature->shouldHaveReceived('setRule')->once();
    });

    it('allows nulls', function () {
        $this->builder->flagged(null);

        $this->mockFeature->shouldHaveReceived('shouldUsePennent')->once();
        $this->mockFeature->shouldNotHaveReceived('setRule');
    });

});
