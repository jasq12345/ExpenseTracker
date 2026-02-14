<?php

namespace App\Service;

use App\Enum\TransactionType;

class AlertBudgetService
{
    public function __construct(

    ) {}

    public function checkBudgetAfterExpense(float $currentBudget, float $expenseAmount, TransactionType $type): bool
    {
        if($type === TransactionType::EXPENSE && ($currentBudget - $expenseAmount) < 0)
        {
            //change it later
            return false;
        }

        return true;
    }
}
