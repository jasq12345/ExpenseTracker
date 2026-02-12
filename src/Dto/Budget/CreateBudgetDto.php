<?php

namespace App\Dto\Budget;

class CreateBudgetDto
{
    public function __construct(
        public ?int $limitAmount
    ){}
}
