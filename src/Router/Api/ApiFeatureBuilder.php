<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Router\Api;

use Gildsmith\CoreApi\Models\User;
use Illuminate\Support\Facades\Route;
use Laravel\Pennant\Feature;
use Laravel\Pennant\Middleware\EnsureFeaturesAreActive;

class ApiFeatureBuilder
{
    protected ApiFeature $feature;

    public function __construct(string $name)
    {
        $this->feature = new ApiFeature();
        $this->feature->setName($name);
    }

    /**
     * Loads a PHP file containing Laravel routes for the
     * route group. The file should define routes using
     * Laravel's routing functions.
     */
    public function file(string $file): self
    {
        $this->callable(function () use ($file) {
            require $file;
        });

        return $this;
    }

    /**
     * Adds a callable to the builder's list of callables.
     *
     * The callable should define routes in the same way
     * as a Laravel route group.
     */
    public function callable(callable $callable): self
    {
        $this->feature->addCallable($callable);

        return $this;
    }

    /**
     * Builds and returns a callable that,when executed,
     * sets up a Laravel route group.
     *
     * The route group will have all routes prefixed with
     * the feature name, following Gildsmith's standard
     * practice for route organization.
     */
    public function build(): callable
    {
        return function () {
            $route = Route::prefix($this->feature->getName());

            if ($this->feature->usesPennent()) {
                Feature::define($this->feature->getName(), $this->feature->getRule());
                $route->middleware(EnsureFeaturesAreActive::using($this->feature->getName()));
            }

            $route->group(function () {
                foreach ($this->feature->getCallables() as $callable) {
                    $callable();
                }
            });
        };
    }

    /**
     * Enables Pennant middleware for the route group.
     *
     * Allows passing a feature flag rule, which is
     * then passed to Pennant's Feature::define.
     *
     * Valid rules can be a single user role, an array
     * of roles, a callback, or null to omit the rule.
     */
    public function flagged(callable|array|string|null $rule = null): self
    {
        $this->feature->shouldUsePennent();

        /*
         * If a single role is passed as a string, convert it to
         * an array so that it can be handled by the next condition.
         */
        if (is_string($rule)) {
            $rule = [$rule];
        }

        /*
         * If an array of roles is passed, create a callable that
         * checks whether the user has one of the specified roles.
         */
        if (is_array($rule)) {
            $rule = fn (User $user) => in_array($user->role->name, $rule);
        }

        /*
         * If the rule is callable, either passed directly or built
         * by previous conditions, set it as the feature's rule.
         */
        if (is_callable($rule)) {
            $this->feature->setRule($rule);
        }

        return $this;
    }
}
