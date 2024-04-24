<?php

namespace Gildsmith\HubApi\Providers;

use Gildsmith\HubApi\Actions\ReadFeatures;
use Gildsmith\HubApi\Gildsmith;
use Gildsmith\HubApi\Http\Middleware\ExpectsFeature;
use Gildsmith\HubApi\Http\Middleware\ForceJsonResponse;
use Gildsmith\HubApi\Router\Web\WebRegistry;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class HubServiceProvider extends ServiceProvider
{
    /**
     * List of actions provided by this package
     * that can be used as artisan commands
     */
    protected array $commands = [
        ReadFeatures::class,
    ];

    public function register(): void
    {
        $this->app->bind('gildsmith', function () {
            return new Gildsmith();
        });
    }

    /** @throws BindingResolutionException */
    public function boot(Kernel $kernel): void
    {
        $this->bootResources();
        $this->bootMiddlewares($kernel);
        $this->defineVersion();
        $this->bootRoutes();
        $this->bootCommands();
    }

    /**
     * Load and merge Gildsmith package resources
     * and setups publishable resources.
     */
    public function bootResources(): void
    {
        $packageBasePath = dirname(__DIR__, 2);

        $this->mergeConfigFrom($packageBasePath.'/config/gildsmith.php', 'gildsmith');
        $this->publishes([$packageBasePath.'/config/gildsmith.php' => config_path('gildsmith.php')], 'config');
    }

    /**
     * Register middleware and configure middleware aliases.
     *
     * @throws BindingResolutionException
     */
    public function bootMiddlewares(Kernel $kernel): void
    {
        $kernel->prependMiddlewareToGroup('api', ForceJsonResponse::class);
        $this->app->make(Router::class)->aliasMiddleware('feature', ExpectsFeature::class);
    }

    /**
     * Defines a 'GILDSMITH_VERSION' constant for
     * easy access throughout the application.
     */
    public function defineVersion(): void
    {
        if (! defined('GILDSMITH_VERSION')) {
            define('GILDSMITH_VERSION', Gildsmith::VERSION);
        }
    }

    /**
     * Initializes web routes handling and
     * defines a route for feature listing.
     */
    public function bootRoutes(): void
    {
        WebRegistry::init();
        Route::get('_gildsmith/features', ReadFeatures::class);
    }

    /**
     * Registers commands defined in the $commands
     * array when running in the console.
     */
    public function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }
}
