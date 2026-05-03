<?php

namespace App\Dto\Budget;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Pagination\PaginationDto;
use App\Validator\Constraint\Pagination\PaginationLimit;
use App\Validator\Constraint\Pagination\PaginationPage;

class ListBudgetDto implements PaginatedDtoInterface
{

    public function __construct(
        #[PaginationPage]
        public int $page = 1,

        #[PaginationLimit]
        public int $limit = 10,
    ) {}

    public function pagination(): PaginationDto
    {
        return new PaginationDto(
            page: $this->page,
            limit: $this->limit,
        );
    }

}
