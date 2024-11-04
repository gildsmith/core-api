<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'decimal' => $this->decimal,
        ];
    }
}
