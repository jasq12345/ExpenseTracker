<?php

namespace App\Dto\Transaction;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Pagination\PaginationDto;
use App\Enum\DtoOrderEnum;
use App\Enum\TransactionType;
use App\Validator\Constraint\Pagination\PaginationLimit;
use App\Validator\Constraint\Pagination\PaginationPage;
use App\Validator\Constraint\Transaction\TransactionName;

readonly class ListTransactionDto implements PaginatedDtoInterface
{
    public function __construct(
        #[PaginationPage]
        public int $page = 1,

        #[PaginationLimit]
        public int $limit = 10,

        public ?array $categories = null,
        #[TransactionName]
        public ?string $name = null,

        // calculated via amount*price not price per one product
        public ?int $minPrice = null,
        public ?int $maxPrice = null,

        public ?TransactionType $type = null,
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
