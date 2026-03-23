<?php

namespace App\Dto\Report;

use App\Enum\TransactionType;
use App\Validator\Constraint\Report\ReportMonth;
use App\Validator\Constraint\Report\ReportYear;

readonly class MonthlyDto extends ReportFilterDto
{
    public function __construct(
        #[ReportMonth]
        public int $month,

        #[ReportYear]
        public int $year,
        ?array $categories = null,
        ?TransactionType $filterType = null,
    ) {
        parent::__construct($categories, $filterType);
    }
}
