<?php

namespace Gildsmith\CoreApi\Console;

use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Illuminate\Console\Command;

class ReadApplications extends Command
{
    public $name = 'gildsmith:apps';

    public $description = 'Lists enabled Gildsmith web applications';

    public function handle(): void
    {
        $apps = WebRegistry::getFullRegistry();

        $this->line('Enabled Gildsmith Web Applications:');

        foreach ($apps as $app) {
            $this->line($this->formatAppForCommand(
                $app->getIdentifier(),
                $app->getRoute(),
                $app->getTemplate()
            ));
        }
    }

    private function formatAppForCommand(string $identifier, string $route, string $template): string
    {
        return "- $identifier (/$route): $template";
    }
}