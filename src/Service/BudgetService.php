<?php

namespace App\Service;

use App\Dto\Budget\CreateBudgetDto;
use App\Dto\Budget\UpdateBudgetDto;
use App\Entity\Budget;
use App\Repository\BudgetRepository;
use App\ValueObject\BudgetPolicy;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;

readonly class BudgetService
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserProviderService $userProvider,
        private BudgetRepository $budgetRepository,
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

        $budget->setLimitAmount($dto->limitAmount);
        $budget->setBudgetPolicy(new BudgetPolicy($dto->policy, $dto->warningThreshold));

        $this->em->flush();

        return $budget;
    }
}
