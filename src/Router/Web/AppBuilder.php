<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Router\Web;

/**
 * Represents a web application (encapsulated frontend app) within
 * the Gildsmith system. Stores essential information for rendering
 * and routing the application.
 */
class AppBuilder
{
    // todo custom control over __get
    public string $identifier;

    public string $template = 'gildsmith.template';

    public string $route = '';

    public array $restricted = [];

    public array $params = [];

    public function __construct(?string $identifier = null)
    {
        $this->identifier = $identifier ?? 'storefront';
    }

    public function template(string $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function route(string $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function restricted(string ...$role): self
    {
        array_push($this->restricted, ...$role);

        return $this;
    }

    // todo expects exactly one argument
    public function param(string $name, mixed $argument): self
    {
        $this->params[$name] = $argument;

        return $this;
    }
}
