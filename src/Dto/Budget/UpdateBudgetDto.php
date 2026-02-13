<?php

namespace App\Dto\Budget;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateBudgetDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $limitAmount
    ){}
}
