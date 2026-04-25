<?php

namespace App\Dto\Pagination;

interface PaginatedDtoInterface
{
    public function pagination(): PaginationDto;
}
