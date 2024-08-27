<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Router\Api;

use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

class ApiFeatureRoutes
{
    protected array $callable = [];

    protected bool $flagged = false;

    public function __construct() {}

    // todo shorthand for loading callable from file
    public function file(string $file): self
    {
        $this->callable(function () use ($file) {
            require $file;
        });

        return $this;
    }

    // todo allows registering custom callable
    public function callable(callable $callable): self
    {
        $this->callable[] = $callable;

        return $this;
    }

    // todo idk is that good?
    public function triggerAll(string $feature): void
    {
        if ($this->flagged) {
            foreach ($this->callable as $callable) {
                Route::middleware(EnsureFeaturesAreActive::using($feature))
                    ->prefix($feature)
                    ->group(function () use ($callable) {
                        $callable();
                    });
            }
        } else {
            foreach ($this->callable as $callable) {
                $callable();
            }
        }
    }

    // todo wraps callable using pennant
    public function flagged(): self
    {
        $this->flagged = true;

        return $this;
    }
}
