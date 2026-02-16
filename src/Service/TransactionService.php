<?php

namespace App\Service;

use App\Dto\Transaction\CreateTransactionDto;
use App\Dto\Transaction\UpdateTransactionDto;
use App\Entity\Transaction;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class TransactionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepository,
        private UserProviderService $userProvider,
    ){}

    public function create(CreateTransactionDto $dto): Transaction
    {
        $user = $this->userProvider->getUser();

        $category = $this->categoryRepository->findOneByIdAndUser($dto->categoryId, $user);

        $transaction = new Transaction();

        $transaction->setName($dto->name);
        $transaction->setAmount($dto->amount);
        $transaction->setPrice($dto->price);
        $transaction->setDescription($dto->description);
        $transaction->setType($dto->type);

        $user->addTransaction($transaction);
        $category->addTransaction($transaction);

        $this->em->persist($transaction);
        $this->em->flush();

        return $transaction;
    }

    public function update(Transaction $transaction, UpdateTransactionDto $dto): Transaction
    {
        $user = $this->userProvider->getUser();

        $category = $this->categoryRepository->findOneByIdAndUser($dto->categoryId, $user);

        $transaction->setName($dto->name);
        $transaction->setDescription($dto->description);

        $category->addTransaction($transaction);

        $this->em->persist($transaction);
        $this->em->flush();

        return $transaction;
    }
}

