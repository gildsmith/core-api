<?php

namespace Gildsmith\HubApi\Actions;

use Gildsmith\HubApi\Router\Api\FeatureRegistry;
use Illuminate\Console\Command;
use Lorisleiva\Actions\Action;
use Lorisleiva\Actions\Concerns\AsAction;
use Lorisleiva\Actions\Concerns\AsCommand;
use Lorisleiva\Actions\Concerns\AsController;

/**
 * This action lists available Gildsmith backend features. Features
 * encapsulate API functionality, can be enabled/disabled, and
 * are used by the frontend for dynamic UI updates.
 */
class ReadFeatures extends Action
{
    use AsAction;
    use AsCommand;
    use AsController;

    public $commandSignature = 'gildsmith:features';

    public $commandDescription = 'Lists enabled Gildsmith features';

    public function asCommand(Command $command): void
    {
        $command->line('Enabled Gildsmith Features: '.$this->asString());
    }

    public function asString(): string
    {
        return implode(', ', $this->handle());
    }

    public function handle(): array
    {
        $features = FeatureRegistry::get();
        sort($features);

        return $features;
    }
}
