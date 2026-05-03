<?php

namespace App\Enum;

enum DtoOrderEnum
{
    case ASC;
    case DESC;

    public function toString(): string
    {
        return match($this) {
            self::ASC => 'ASC',
            self::DESC => 'DESC',
        };
    }
}
