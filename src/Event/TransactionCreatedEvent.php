<?php

namespace App\Event;

use App\Entity\Budget;
use App\Entity\Transaction;

readonly class TransactionCreatedEvent
{
    public function __construct(
        public Budget $budget,
        public Transaction $transaction,
    ) {}

    public function getBudget(): Budget
    {
        return $this->budget;
    }
}
