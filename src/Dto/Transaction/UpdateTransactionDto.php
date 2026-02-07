<?php

namespace App\Dto\Transaction;


class UpdateTransactionDto
{
    public function __construct(
        public string $name,
        public ?int $categoryId = null,
        public ?string $description = null,
    ){}
}
