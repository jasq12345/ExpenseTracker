<?php

namespace App\Service;

use App\Dto\Transaction\CreateTransactionDto;
use App\Dto\Transaction\UpdateTransactionDto;
use App\Entity\Transaction;
use App\Event\TransactionCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

readonly class TransactionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryService $categoryService,
        private UserProviderService $userProvider,
        private BudgetService $budgetService,
        private EventDispatcherInterface $eventDispatcher,
    ){}

    public function create(CreateTransactionDto $dto): Transaction
    {
        $user = $this->userProvider->getUser();
        $budget = $this->budgetService->getCurrentBudget($user);
        $category = $this->categoryService->getByIdAndUser($dto->categoryId, $user);
        $totalAmount = $dto->amount * $dto->price;

        $transaction = $this->createTransaction($dto);

        $user->addTransaction($transaction);
        $category->addTransaction($transaction);

        $this->budgetService->applyTransaction($budget, $totalAmount, $dto->type);

        $this->em->persist($transaction);
        $this->em->flush();

        $this->eventDispatcher->dispatch(new TransactionCreatedEvent($budget, $transaction));

        return $transaction;
    }
    public function update(Transaction $transaction, UpdateTransactionDto $dto): Transaction
    {
        $user = $this->userProvider->getUser();

        $category = $this->categoryService->getByIdAndUser($dto->categoryId, $user);

        $transaction->setName($dto->name);
        $transaction->setDescription($dto->description);

        $category->addTransaction($transaction);

        $this->em->persist($transaction);
        $this->em->flush();

        return $transaction;
    }

    private function createTransaction(CreateTransactionDto $dto): Transaction
    {
        $transaction = new Transaction();

        $transaction->setName($dto->name);
        $transaction->setAmount($dto->amount);
        $transaction->setPrice($dto->price);
        $transaction->setDescription($dto->description);
        $transaction->setType($dto->type);

        return $transaction;
    }
}

