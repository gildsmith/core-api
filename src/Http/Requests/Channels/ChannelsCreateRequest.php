<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Requests\Channels;

use Gildsmith\CoreApi\Http\Requests\FormRequest;
use Illuminate\Support\Facades\Gate;

class ChannelsCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('role', 'admin');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }
}
