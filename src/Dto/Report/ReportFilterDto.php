<?php

namespace App\Dto\Report;

use App\Enum\TransactionType;

readonly class ReportFilterDto
{
    public function __construct(
        public ?array $categories = null,
        public ?TransactionType $filterType = null,
    ) {}
}
