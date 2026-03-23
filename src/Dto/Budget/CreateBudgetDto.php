<?php

namespace App\Dto\Budget;

use App\Enum\BudgetPolicyEnum;
use App\Validator\Constraint\Budget\BudgetLimitAmount;
use App\Validator\Constraint\Budget\BudgetPolicyThresholdConsistency;
use App\Validator\Constraint\Budget\BudgetWarningThreshold;

#[BudgetPolicyThresholdConsistency]
readonly class CreateBudgetDto
{
    public function __construct(
        #[BudgetLimitAmount]
        public string $limitAmount,

        public BudgetPolicyEnum $policy = BudgetPolicyEnum::STRICT,

        #[BudgetWarningThreshold]
        public ?int $warningThreshold = 80,
    ) {}
}
