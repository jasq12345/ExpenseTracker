<?php

namespace App\Service;

use App\Dto\Transaction\CreateTransactionDto;
use App\Dto\Transaction\UpdateTransactionDto;
use App\Entity\Transaction;
use App\Enum\TransactionType;
use App\Repository\BudgetRepository;
use App\Repository\CategoryRepository;
use App\Service\Notificaation\BudgetAlertService;
use Doctrine\ORM\EntityManagerInterface;

readonly class TransactionService
{
    public function __construct(
        private EntityManagerInterface $em,
        private CategoryRepository $categoryRepository,
        private UserProviderService $userProvider,
        private BudgetRepository $budgetRepository,
        private BudgetAlertService $budgetAlertService,
    ){}

    public function create(CreateTransactionDto $dto): Transaction
    {
        $user = $this->userProvider->getUser();
        $budget = $this->budgetRepository->findCurrentBudgetByUser($user);

        if(!$budget){
            throw new \DomainException(
                'No active budget for this month. Please create a budget before making transactions.'
            );
        }

        $totalAmount = $dto->amount * $dto->price;
        $category = $this->categoryRepository->findOneByIdAndUser($dto->categoryId, $user);

        $transaction = $this->createTransaction($dto);

        $user->addTransaction($transaction);
        $category->addTransaction($transaction);

        match ($dto->type) {
            TransactionType::EXPENSE => $budget->addExpense($totalAmount),
            TransactionType::INCOME  => $budget->addIncome($totalAmount),
        };

        $this->em->persist($transaction);
        $this->em->flush();

        if ($dto->type === TransactionType::EXPENSE) {
            $this->budgetAlertService->checkAndAlert($budget);
        }

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

