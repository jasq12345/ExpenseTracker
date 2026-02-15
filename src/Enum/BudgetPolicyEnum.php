<?php

namespace App\Enum;

enum BudgetPolicyEnum: string
{
    case STRICT = 'strict';
    case FLEXIBLE = 'flexible';
    case UNLIMITED = 'unlimited';

    public function requiresWarningThreshold(): bool
    {
        return $this !== self::UNLIMITED;
    }

}
