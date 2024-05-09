<?php

namespace Gildsmith\HubApi\Actions;

use Gildsmith\HubApi\Router\Web\WebRegistry;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;
use stdClass;

/**
 * TODO
 */
class ReadApplications extends Action
{
    use AsAction, AsCommand, AsController;

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

    public function handle(): array
    {
        WebRegistry::init();

        return [...WebRegistry::getRegistry(), WebRegistry::fallback()];
    }

    /**
     * TODO
     */
    public function asController(?string $app = null): JsonResponse
    {
        if (empty($app)) {
            $response = $this->handle();
        } else {
            $filteredApps = array_filter($this->handle(), function ($registeredApp) use ($app) {
                return $registeredApp->identifier === $app;
            });

            $response = count($filteredApps) > 0
                ? array_shift($filteredApps)
                : new stdClass();
        }

        return response()->json($response);
    }
}
