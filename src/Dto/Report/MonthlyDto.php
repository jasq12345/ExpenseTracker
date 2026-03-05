<?php

namespace App\Dto\Report;

use App\Enum\TransactionType;
use Symfony\Component\Validator\Constraints as Assert;
class MonthlyDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $month,

        #[Assert\NotBlank]
        public int $year,

        #[Assert\NotBlank]
        public ?array $categories = null,

        #[Assert\NotBlank]
        public ?TransactionType $filterType = null
    ) {}
}
