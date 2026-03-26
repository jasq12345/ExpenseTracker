<?php

namespace App\Factory;

use App\Entity\Budget;
use App\Factory\ValueObject\BudgetPolicyFactory;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Budget>
 */
final class BudgetFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Budget::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return function (): array {
            $limit = self::faker()->randomFloat(2, 300, 8000);
            $spent = self::faker()->randomFloat(2, 0, $limit);
            $current = $limit - $spent;

            return [
                'budgetPolicy' => BudgetPolicyFactory::new(),
                'limitAmount' => number_format($limit, 2, '.', ''),
                'spentAmount' => number_format($spent, 2, '.', ''),
                'currentAmount' => number_format($current, 2, '.', ''),
                'month' => self::faker()->numberBetween(1, 12),
                'year' => (int) date('Y'),
                'user' => UserFactory::new(),
            ];
        };
    }
}
