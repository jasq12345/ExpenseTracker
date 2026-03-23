<?php

namespace App\Dto\Report;

use App\Enum\TransactionType;
use App\Validator\Constraint\Report\ReportYear;

readonly class YearlyDto extends ReportFilterDto
{
    public function __construct(
        #[ReportYear]
        public int $year,
        ?array $categories = null,
        ?TransactionType $filterType = null,
    ) {
        parent::__construct($categories, $filterType);
    }
}
