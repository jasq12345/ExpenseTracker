<?php

namespace App\Service;

use App\Dto\Report\MonthlyDto;
use App\Dto\Report\ReportFilterDto;
use App\Dto\Report\YearlyDto;
use App\Entity\Category;
use App\Entity\User;
use App\Enum\TransactionType;
use App\Repository\TransactionRepository;
use App\ValueObject\Period;
use DateMalformedStringException;
use DateTimeImmutable;

readonly class ReportService
{
    public function __construct(
        private TransactionRepository $transactionRepository,
        private UserProviderService $userProvider,
    ) {}

    /**
     * @throws DateMalformedStringException
     */
    public function getMonthlyReport(MonthlyDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $period = Period::forMonth($dto->year, $dto->month);

        return $this->buildReport($user, $period, $dto);
    }

    public function getYearlyReport(YearlyDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $period = Period::forYear($dto->year);

        return $this->buildReport($user, $period, $dto);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getWeeklyReport(ReportFilterDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $period = Period::forWeek();

        return $this->buildReport($user, $period, $dto);
    }

    public function getDailyReport(ReportFilterDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $period = Period::forDay();

        return $this->buildReport($user, $period, $dto);
    }

    public function getAllTimeReport(ReportFilterDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $period = Period::allTime();

        return $this->buildReport($user, $period, $dto);
    }

    private function buildReport(
        User $user,
        Period $period,
        ReportFilterDto $dto,
    ): array
    {
        $expenses = $this->getTotal($user, TransactionType::EXPENSE, $period, $dto);
        $income = $this->getTotal($user, TransactionType::INCOME, $period, $dto);

        return [
            'startDate' => $period->getStartDate()?->format('Y-m-d'),
            'endDate' => $period->getEndDate()?->format('Y-m-d'),
            'categories' => $dto->categories ? array_map(fn(Category $c) => $c->getName(), $dto->categories) : null,
            'filterType' => $dto->filterType?->value,
            'totalExpenses' => $expenses,
            'totalIncome' => $income,
            'netBalance' => $income - $expenses,
        ];
    }

    private function getTotal(
        User $user,
        TransactionType $type,
        Period $period,
        ReportFilterDto $dto,
    ): float
    {
        if ($dto->filterType !== null && $dto->filterType !== $type) {
            return 0.0;
        }

        return $this->transactionRepository->getTotalByPeriodAndType($user, $type, $period, $dto->categories);
    }
}
