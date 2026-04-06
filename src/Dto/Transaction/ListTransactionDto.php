<?php

namespace App\Dto\Transaction;

use App\Dto\Pagination\PaginationDto;
use App\Enum\TransactionType;

readonly class ListTransactionDto extends PaginationDto
{
        public function __construct(
            int $page = 1,
            int $limit = 10,
            public ?array $categories = null,
            public ?string $name = null,

            //calculated via amount*price not price per one product
            public ?int $minPrice = null,
            public ?int $maxPrice = null,

            public ?TransactionType $type = null,
        ) {
            parent::__construct($page, $limit);
        }
}
