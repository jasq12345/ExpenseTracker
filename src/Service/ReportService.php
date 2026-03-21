<?php

namespace App\Service;

use App\Dto\Report\MonthlyDto;
use App\Dto\Report\ReportFilterDto;
use App\Dto\Report\YearlyDto;
use App\Entity\Category;
use App\Entity\User;
use App\Enum\TransactionType;
use App\Repository\TransactionRepository;
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

        $startDate = new DateTimeImmutable("$dto->year-$dto->month-01");
        $endDate = $startDate->modify('last day of this month')->setTime(23, 59, 59);

        return $this->buildReport($user, $startDate, $endDate, $dto->categories, $dto->filterType);
    }

    public function getYearlyReport(YearlyDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $startDate = new DateTimeImmutable("$dto->year-01-01");
        $endDate = new DateTimeImmutable("$dto->year-12-31 23:59:59");

        return $this->buildReport($user, $startDate, $endDate,  $dto->categories, $dto->filterType);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getWeeklyReport(ReportFilterDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $now = new DateTimeImmutable();
        $startDate = $now->modify('monday this week')->setTime(0, 0);
        $endDate = $now->modify('sunday this week')->setTime(23, 59, 59);

        return $this->buildReport($user, $startDate, $endDate, $dto->categories, $dto->filterType);
    }

    public function getDailyReport(ReportFilterDto $dto): array
    {
        $user = $this->userProvider->getUser();

        $startDate = new DateTimeImmutable('today 00:00:00');
        $endDate = new DateTimeImmutable('today 23:59:59');

        return $this->buildReport($user, $startDate, $endDate, $dto->categories, $dto->filterType);
    }

    public function getAllTimeReport(ReportFilterDto $dto): array
    {
        $user = $this->userProvider->getUser();

        return $this->buildReport($user, null, null, $dto->categories, $dto->filterType);
    }

    private function buildReport(
        User $user,
        ?DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        ?array $categories,
        ?TransactionType $filterType
    ): array
    {

        $expenses = $this->getTotal($user, TransactionType::EXPENSE, $startDate, $endDate, $categories, $filterType);
        $income = $this->getTotal($user, TransactionType::INCOME, $startDate, $endDate, $categories, $filterType);

        return [
            'startDate' => $startDate?->format('Y-m-d'),
            'endDate' => $endDate?->format('Y-m-d'),
            'categories' => $categories ? array_map(fn(Category $c) => $c->getName(), $categories) : null,
            'filterType' => $filterType?->value,
            'totalExpenses' => $expenses,
            'totalIncome' => $income,
            'netBalance' => $income - $expenses,
        ];
    }

    private function getTotal(
        User $user,
        TransactionType $type,
        ?DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        ?array $categories,
        ?TransactionType $filterType
    ): float
    {
        if ($filterType !== null && $filterType !== $type) {
            return 0.0;
        }

        return $this->transactionRepository->getTotalByPeriodAndType($user, $type, $startDate, $endDate, $categories);
    }
}
