<?php

namespace App\Service;

use App\Dto\Transaction\CreateTransactionDto;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class TransactionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private TransactionRepository $transactionRepository,
    ){}

    public function create(CreateTransactionDto $dto): Transaction
    {
        $transaction = new Transaction();
        $category = $this->transactionRepository->findOneBy(['id' => $dto->categoryId]);

        $transaction->setName($dto->name);
        $transaction->setAmount($dto->amount);
        $transaction->setPrice($dto->price);
        $transaction->setCategory($category);
        $transaction->setDate($dto->date);
        $transaction->setDescription($dto->description);
        $transaction->setType($dto->type);

        $category->addTransaction($transaction);

        $this->em->persist($transaction);
        $this->em->flush();

        return $transaction;
    }
}

