<?php

namespace App\Dto\Report;

use App\Enum\TransactionType;
use App\Validator\Constraint\Report\ReportCategories;

readonly class ReportFilterDto
{
    public function __construct(
        /* @var int[]|null categories*/
        #[ReportCategories]
        public ?array $categories = null,
        public ?TransactionType $filterType = null,
    ) {}
}
