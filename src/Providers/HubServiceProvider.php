<?php

declare(strict_types=1);

namespace Gildsmith\HubApi\Providers;

use Gildsmith\HubApi\Actions\ReadApplications;
use Gildsmith\HubApi\Facades\Gildsmith;
use Gildsmith\HubApi\Http\Middleware\ForceJsonResponse;
use Gildsmith\HubApi\Http\Middleware\SetLanguage;
use Gildsmith\HubApi\Models\User;
use Gildsmith\HubApi\Router\Web\WebRegistry;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class HubServiceProvider extends ServiceProvider
{
    /**
     * List of actions provided by this package
     * that can be used as artisan commands
     */
    protected array $commands = [
        ReadApplications::class,
    ];

    public function register(): void
    {
        $this->app->bind('gildsmith', fn () => new \Gildsmith\HubApi\Gildsmith());
    }

    /**
     * @throws BindingResolutionException
     */
    public function boot(Kernel $kernel): void
    {
        $this->bootResources();
        $this->bootMiddlewares($kernel);
        $this->bootRoutes();
        $this->bootApiFeatures();
        $this->bootCommands();
        $this->bootBroadcastChannels();
    }

    /**
     * Load and merge Gildsmith package resources
     * and setups publishable resources.
     */
    public function bootResources(): void
    {
        $this->loadMigrationsFrom(dirname(__DIR__, 2).'/database/migrations');

        include_once base_path('bootstrap/gildsmith.php');
    }

    /**
     * Register middleware and configure middleware aliases.
     */
    public function bootMiddlewares(Kernel $kernel): void
    {
        $kernel->prependMiddlewareToGroup('api', SetLanguage::class);
        $kernel->prependMiddlewareToGroup('api', ForceJsonResponse::class);
    }

    /**
     * Initializes web routes handling and
     * defines a route for feature listing.
     */
    public function bootRoutes(): void
    {
        WebRegistry::init();

        Route::middleware(EnsureFrontendRequestsAreStateful::class)->group(function () {
            require dirname(__DIR__, 2).'/routes/_gildsmith.php';
        });
    }

    /**
     * Enables specific API features and registers
     * their corresponding endpoints.
     */
    protected function bootApiFeatures(): void
    {
        Gildsmith::feature('channels')
            ->file(dirname(__DIR__, 2).'/routes/channels.php')
            ->flagged();
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

    public function bootBroadcastChannels(): void
    {
        Broadcast::channel('gildsmith.dashboard.channels', function (User $user) {
            return $user->role->name === 'admin';
        });
    }
}
