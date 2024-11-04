<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'default_currency' => CurrencyResource::make($this->defaultCurrency),
            'default_language' => LanguageResource::make($this->defaultLanguage),
            'currencies' => CurrencyResource::collection($this->currencies),
            'languages' => LanguageResource::collection($this->languages),
        ];
    }
}
