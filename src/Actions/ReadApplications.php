<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Actions;

use Gildsmith\CoreApi\Router\Web\AppBuilder;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;

class ReadApplications extends Action
{
    use AsAction;
    use AsCommand;
    use AsController;

    public $commandSignature = 'gildsmith:apps';

    public $commandDescription = 'Lists enabled Gildsmith web applications';

    /** @noinspection PhpUnused */
    public function asCommand(Command $command): void
    {
        $command->line('Enabled Gildsmith Web Applications:');

        $apps = $this->handle();

        foreach ($apps as $app) {
            $command->line("- $app->identifier (/$app->route): [$app->template]");
        }
    }

    /*
     * Handles retrieving the registered applications, filtered by role restrictions.
     * If a role is provided, only returns apps accessible by that role.
     */
    public function handle(?string $role = null, ?string $app = null): array
    {
        WebRegistry::init();

        /** @var AppBuilder[] $apps */
        $apps = [...WebRegistry::getRegistry(), WebRegistry::fallback()];

        // Filter applications by role
        $apps = $role === null ? $apps : array_filter($apps, function ($app) use ($role) {
            return empty($app->restricted) || in_array($role, $app->restricted);
        });

        if ($app !== null) {
            $filteredApps = array_filter($apps, function ($registeredApp) use ($app) {
                return $registeredApp->identifier === $app;
            });

            // Return the specific app or an empty object if not found
            return count($filteredApps) > 0
                ? [array_shift($filteredApps)]
                : [];
        }

        return $apps;
    }

    /**
     * Returns all registered apps available to the user based on their role.
     * Optionally filters by a specific app by its identifier if provided.
     */
    public function asController(ActionRequest $request, ?string $app = null): JsonResponse
    {
        $role = $request->user()?->role->name ?? 'guest';

        $response = $this->handle($role, $app);

        return response()->json(array_values($response));
    }
}
