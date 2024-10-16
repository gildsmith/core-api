<?php

declare(strict_types=1);

namespace Gildsmith\CoreApi\Enums;

enum UserRoleEnum: int
{
    case USER = 1;
    case ADMIN = 2;

    public function label(): string
    {
        return strtolower($this->name);
    }

    public function id(): int
    {
        return $this->value;
    }
}
