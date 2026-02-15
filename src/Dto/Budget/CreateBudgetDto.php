<?php

namespace App\Dto\Budget;

use App\Enum\BudgetPolicyEnum;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateBudgetDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public string $limitAmount,

        public BudgetPolicyEnum $policy = BudgetPolicyEnum::STRICT,

        #[Assert\PositiveOrZero]
        public ?int $warningThreshold = 80,
    ) {}
}
