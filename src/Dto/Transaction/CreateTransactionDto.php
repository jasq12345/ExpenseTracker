<?php

namespace App\Dto\Transaction;

use App\Enum\TransactionType;

class CreateTransactionDto
{
    public function __construct(
        public string $name,
        public int $amount,
        public int $price,
        public ?int $categoryId = null,
        public ?string $description = null,
        public TransactionType $type
    ){}
}
