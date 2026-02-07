<?php
namespace App\Enum;

enum TransactionType: string
{
    case EXPENSE = 'expense';
    case INCOME = 'income';

    public function label(): string
    {
        return match($this) {
            self::EXPENSE => 'expense',
            self::INCOME => 'income',
        };
    }

    public function isExpense(): bool
    {
        return $this === self::EXPENSE;
    }

    public function isIncome(): bool
    {
        return $this === self::INCOME;
    }
}
