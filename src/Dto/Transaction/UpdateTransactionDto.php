<?php

namespace App\Dto\Transaction;


use App\Validator\Constraint\Transaction\TransactionCategoryId;
use App\Validator\Constraint\Transaction\TransactionDescription;
use App\Validator\Constraint\Transaction\TransactionName;

class UpdateTransactionDto
{
    public function __construct(
        #[TransactionName]
        public string $name,

        #[TransactionCategoryId]
        public ?int $categoryId = null,

        #[TransactionDescription]
        public ?string $description = null,
    ){}
}
