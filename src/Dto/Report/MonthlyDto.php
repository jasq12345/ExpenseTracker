<?php

namespace App\Dto\Report;

use App\Enum\TransactionType;
use Symfony\Component\Validator\Constraints as Assert;

readonly class MonthlyDto extends ReportFilterDto
{
    public function __construct(
        #[Assert\NotBlank]
        public int $month,

        #[Assert\NotBlank]
        public int $year,
        ?array $categories = null,
        ?TransactionType $filterType = null,
    ) {
        parent::__construct($categories, $filterType);
    }
}
