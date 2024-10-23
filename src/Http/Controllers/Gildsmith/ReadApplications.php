<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Controllers\Gildsmith;

use Gildsmith\CoreApi\Http\Controllers\Controller;
use Gildsmith\CoreApi\Router\Web\WebRegistry;
use Gildsmith\CoreApi\Utils\ApplicationFilter;
use Illuminate\Http\Request;

class ReadApplications extends Controller
{
    protected WebRegistry $webRegistry;

    public function __construct(WebRegistry $webRegistry = null)
    {
        $this->webRegistry = $webRegistry ?? new WebRegistry();
    }

    /**
     * Returns all registered apps available to the user based on their role.
     * Optionally filters by a specific app by its identifier if provided.
     */
    public function __invoke(Request $request, ?string $app = null)
    {
        $role = $request->user()?->role->name ?? 'guest';

        $response = ApplicationFilter::filter($this->webRegistry, $role, $app);

        return response()->json(array_values($response));
    }
}
