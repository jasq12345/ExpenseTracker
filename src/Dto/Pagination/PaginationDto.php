<?php

namespace App\Dto\Pagination;

use App\Validator\Constraint\Pagination\PaginationLimit;
use App\Validator\Constraint\Pagination\PaginationPage;

readonly class PaginationDto implements PaginatedDtoInterface
{
    public function __construct(
        #[PaginationPage]
        public int $page = 1,

        #[PaginationLimit]
        public int $limit = 10,
    ) {}
    public function pagination(): self
    {
        return $this;
    }
}
