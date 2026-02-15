<?php

namespace App\Dto\Budget;

use App\Enum\BudgetPolicyEnum;
use Symfony\Component\Validator\Constraints as Assert;

readonly class UpdateBudgetDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $limitAmount,

        public BudgetPolicyEnum $policy = BudgetPolicyEnum::STRICT,

        #[Assert\PositiveOrZero]
        public ?int $warningThreshold = 80,
    ){}
}
