<?php

namespace App\Enum;

enum BudgetPolicyEnum: string
{
    Case STRICT = 'strict';
    Case FLEXIBLE = 'lenient';
    case UNLIMITED = 'unlimited';
}
