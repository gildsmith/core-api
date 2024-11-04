<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Requests\Channels;

use Gildsmith\CoreApi\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Gate;

class ChannelsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('role', 'admin');
    }
}
