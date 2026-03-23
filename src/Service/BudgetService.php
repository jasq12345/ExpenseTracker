<?php

namespace App\Service;

use App\Dto\Budget\CreateBudgetDto;
use App\Dto\Budget\UpdateBudgetDto;
use App\Entity\Budget;
use App\Entity\User;
use App\Entity\ValueObject\BudgetPolicy;
use App\Enum\BudgetPolicyEnum;
use App\Enum\TransactionType;
use App\Exception\DomainException\BudgetLimitExceededException;
use App\Exception\DomainException\BudgetNotFoundException;
use App\Exception\DomainException\InsufficientFundsException;
use App\Guard\BudgetGuard;
use App\Repository\BudgetRepository;
use App\Service\Notification\BudgetAlertService;
use Doctrine\ORM\EntityManagerInterface;
use DomainException;
use LogicException;

readonly class BudgetService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserProviderService $userProvider,
        private BudgetRepository $budgetRepository,
        private BudgetGuard $budgetGuard,
    ) {}

    public function create(CreateBudgetDto $dto): Budget
    {
        $user = $this->userProvider->getUser();

        if($this->budgetRepository->existsCurrentBudget($user)) {
            throw new LogicException('Budget for this month already exists.');
        }

        $budget = new Budget();

        $budget->setLimitAmount($dto->limitAmount);
        $budget->setBudgetPolicy(new BudgetPolicy($dto->policy, $dto->warningThreshold));

        $user->addBudget($budget);

        $this->em->persist($budget);
        $this->em->flush();

        return $budget;
    }

    public function update(UpdateBudgetDto $dto): Budget
    {
        $user = $this->userProvider->getUser();

        $budget = $this->budgetRepository->findCurrentBudgetByUser($user);

        if(!$budget) {
            throw new LogicException('Budget for this month not found.');
        }

        $budget->setLimitAmount($dto->limitAmount);
        $budget->setBudgetPolicy(new BudgetPolicy($dto->policy, $dto->warningThreshold));

        $this->em->flush();

        return $budget;
    }

    /**
     * Applies a transaction to the budget.
     * Remember to flush the entity manager after calling this method.
     *
     * @param Budget $budget
     * @param float $amount
     * @param TransactionType $type
     */
    public function applyTransaction(Budget $budget, float $amount, TransactionType $type): void
    {
        if ($type === TransactionType::EXPENSE) {
            if (!$this->budgetGuard->canAddExpense($budget, $amount)) {
                $policy = $budget->getBudgetPolicy()->getPolicy();

                throw match ($policy) {
                    BudgetPolicyEnum::STRICT   => new BudgetLimitExceededException('Cannot go below minimum balance.'),
                    default                    => new InsufficientFundsException('Insufficient funds.'),
                };
            }
            $budget->addExpense($amount);
        } else {
            $budget->addIncome($amount);
        }
    }

    public function getCurrentBudget(User $user): Budget
    {
        $budget = $this->budgetRepository->findCurrentBudgetByUser($user);

        if(!$budget) {
            throw new BudgetNotFoundException('No active budget for this month. Please create a budget before making transactions.');
        }

        return $budget;
    }
}
