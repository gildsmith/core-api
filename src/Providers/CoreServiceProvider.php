<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Providers;

use Gildsmith\CoreApi\Actions\ReadApplications;
use Gildsmith\CoreApi\Facades\Gildsmith;
use Gildsmith\CoreApi\Http\Middleware\ForceJsonResponse;
use Gildsmith\CoreApi\Http\Middleware\SetLanguage;
use Gildsmith\CoreApi\Models\User;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Pennant\Feature;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * List of actions provided by this package
     * that can be executed as Artisan commands.
     */
    protected array $commands = [
        ReadApplications::class,
    ];

    public function register(): void
    {
        $this->app->bind('gildsmith', fn () => new \Gildsmith\CoreApi\Gildsmith);
    }

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
     * Loads and merges Gildsmith package resources
     * and setups publishable resources.
     */
    public function bootResources(): void
    {
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));

        include_once base_path('bootstrap/gildsmith.php');
    }

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
            require $this->packagePath('routes/_gildsmith.php');
        });
    }

    /**
     * Enables specific API features and registers
     * their corresponding endpoints.
     */
    protected function bootApiFeatures(): void
    {
        Gildsmith::feature('channels')
            ->file($this->packagePath('routes/channels.php'))
            ->flagged('admin');
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

    /**
     * Helper function to build paths from the package root.
     */
    private function packagePath(string $path): string
    {
        return dirname(__DIR__, 2).'/'.$path;
    }
}