<?php

namespace App\Dto\Transaction;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Pagination\PaginationDto;
use App\Enum\TransactionType;

readonly class ListTransactionDto implements PaginatedDtoInterface
{
    public function __construct(
        public PaginationDto $pagination,
        public ?array $categories = null,
        public ?string $name = null,

        //calculated via amount*price not price per one product
        public ?int $minPrice = null,
        public ?int $maxPrice = null,

        public ?TransactionType $type = null,
    ){}

    public function pagination(): PaginationDto
    {
        return $this->pagination;
    }
}
