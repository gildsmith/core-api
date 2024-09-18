<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Router\Api;

use Closure;

class ApiFeature
{
    /**
     * Feature name used for route prefixing
     * and Laravel Pennant configuration.
     */
    protected string $name;

    /**
     * @var callable[] An array of callables for route groups.
     *
     * @see https://laravel.com/docs/11.x/routing#route-groups
     */
    protected array $callables = [];

    /**
     * Determines whether to wrap route groups
     * in Laravel Pennant middleware.
     */
    protected bool $pennent = false;

    /**
     * A Closure that defines the Pennant feature flag rule.
     * Defaults to null, meaning the feature is available
     * to everyone if no rule is defined.
     */
    protected ?Closure $rule = null;

    public function getRule(): ?callable
    {
        return $this->rule;
    }

    public function setRule(callable $rule): void
    {
        $this->rule = $rule;
    }

    public function getName(): ?string
    {
        return $this->name ?? null;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCallables(): array
    {
        return $this->callables;
    }

    public function usesPennent(): bool
    {
        return $this->pennent;
    }

    public function addCallable(callable $callable): void
    {
        $this->callables[] = $callable;
    }

    public function shouldUsePennent(): void
    {
        $this->pennent = true;
    }
}
