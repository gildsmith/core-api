<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Router\Web;

use JsonSerializable;

/**
 * Represents a web application (encapsulated frontend app) within
 * the Gildsmith system. Stores essential information for rendering
 * and routing the application.
 *
 * TODO this should likely be broken into WebApp
 */
class WebAppBuilder implements JsonSerializable
{
    // Unique identifier for the app instance.
    protected readonly string $identifier;

    // Blade template used to render the frontend app.
    protected string $template = 'gildsmith.template';

    // Root directory route that redirects all calls to
    // the specified template.
    protected string $route = '';

    // Groups that can see the app in the app listing.
    // This does not necessarily grant access to the app.
    protected array $groups = [];

    // Optional parameters passed to the template.
    protected array $params = [];

    public function __construct(?string $identifier = null)
    {
        $this->identifier = $identifier ?? 'storefront';
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function setGroups(string ...$role): self
    {
        array_push($this->groups, ...$role);

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
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

    public function restrictedTo(string ...$role): self
    {
        $this->setGroups(...$role);

        return $this;
    }

    public function param(string $name, mixed $argument): self
    {
        $this->params[$name] = $argument;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'identifier' => $this->identifier,
            'template' => $this->template,
            'route' => $this->route,
            'groups' => $this->groups,
            'params' => $this->params
        ];
    }
}
