<?php

namespace App\Factory\ValueObject;

use App\Entity\ValueObject\BudgetPolicy;
use App\Enum\BudgetPolicyEnum;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<BudgetPolicy>
 */
final class BudgetPolicyFactory extends ObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return BudgetPolicy::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return function (): array {
            $policy = self::faker()->randomElement(BudgetPolicyEnum::cases());

            return [
                'policy' => $policy,
                'warningThreshold' => $policy->requiresWarningThreshold()
                    ? self::faker()->numberBetween(50, 95)
                    : null,
            ];
        };
    }
}
