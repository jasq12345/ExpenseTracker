<?php

namespace App\Service;

use App\Dto\Transaction\CreateTransactionDto;
use App\Dto\Transaction\UpdateTransactionDto;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use App\Guard\BudgetGuard;
use App\Service\Notification\BudgetAlertService;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;

readonly class TransactionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryService $categoryService,
        private UserProviderService $userProvider,
        private BudgetService $budgetService,
        private BudgetGuard $budgetGuard,
        private BudgetAlertService $alertService,  // Add this
    ){}

    public function create(CreateTransactionDto $dto): Transaction
    {
        $user = $this->userProvider->getUser();
        $budget = $this->budgetService->getCurrentBudget($user);
        $category = $this->categoryService->getByIdAndUser($dto->categoryId, $user);
        $totalAmount = $dto->amount * $dto->price;

        if(!$this->budgetGuard->canAddExpense($budget, $totalAmount)) {
            $this->alertService->checkAndAlert($budget);
            throw new DomainException('Budget limit exceeded');
        }

        $transaction = $this->createTransaction($dto);

        $user->addTransaction($transaction);
        $category->addTransaction($transaction);

        $this->budgetService->applyTransaction($budget, $totalAmount, $dto->type);

        $this->em->persist($transaction);
        $this->em->flush();

        $this->alertService->checkAndAlert($budget);

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

