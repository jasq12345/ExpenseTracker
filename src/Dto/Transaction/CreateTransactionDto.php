<?php

namespace App\Dto\Transaction;

use App\Enum\TransactionType;
use App\Validator\Constraint\Transaction\TransactionAmount;
use App\Validator\Constraint\Transaction\TransactionCategoryId;
use App\Validator\Constraint\Transaction\TransactionDescription;
use App\Validator\Constraint\Transaction\TransactionName;
use App\Validator\Constraint\Transaction\TransactionPrice;
use Symfony\Component\Validator\Constraints as Assert;

class CreateTransactionDto
{
    public function __construct(
        #[TransactionName]
        public string $name,

        #[TransactionAmount]
        public int $amount,

        #[TransactionPrice]
        public int $price,

        #[TransactionCategoryId]
        public ?int $categoryId = null,

        #[TransactionDescription]
        public ?string $description = null,

        public TransactionType $type = TransactionType::EXPENSE,
    ) {}
}
