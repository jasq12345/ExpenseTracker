<?php

namespace App\Dto\Transaction;

use App\Enum\TransactionType;

readonly class ShowTransactionDto
{
 public function __construct(
        public int $id,
        public ?array $categories = null,
        public ?string $name = null,

        //calculated via amount*price not price per one product
        public ?int $minPrice = null,
        public ?int $maxPrice = null,

        public ?TransactionType $type = null,
    ){}
}
