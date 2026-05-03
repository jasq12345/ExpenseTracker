<?php

namespace App\Dto\Category;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Pagination\PaginationDto;
use App\Enum\DtoOrderEnum;
use App\Validator\Constraint\Category\CategoryColor;
use App\Validator\Constraint\Category\CategoryIcon;
use App\Validator\Constraint\Category\CategoryName;
use App\Validator\Constraint\Pagination\PaginationLimit;
use App\Validator\Constraint\Pagination\PaginationPage;

readonly class ListCategoryDto implements PaginatedDtoInterface
{
    public function __construct(
        #[PaginationPage]
        public int $page = 1,

        #[PaginationLimit]
        public int $limit = 10,

        #[CategoryName]
        public ?string $name = null,

        #[CategoryColor]
        public ?string $color = null,

        #[CategoryIcon]
        public ?string $icon = null,

        public DtoOrderEnum $orderBy = DtoOrderEnum::DESC,
    ) {}

    public function pagination(): PaginationDto
    {
        return new PaginationDto(
            page: $this->page,
            limit: $this->limit,
        );
    }
}
