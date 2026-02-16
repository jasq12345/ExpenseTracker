<?php

namespace App\Service;

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
    public function getMonthlyReport(int $month, int $year, ?array $categories = null, ?TransactionType $filterType = null): array
    {
        $startDate = new DateTimeImmutable("$year-$month-01");
        $endDate = $startDate->modify('last day of this month')->setTime(23, 59, 59);

        return $this->buildReport($startDate, $endDate, $categories, $filterType);
    }

    public function getYearlyReport(int $year, ?array $categories = null, ?TransactionType $filterType = null): array
    {
        $startDate = new DateTimeImmutable("$year-01-01");
        $endDate = new DateTimeImmutable("$year-12-31 23:59:59");

        return $this->buildReport($startDate, $endDate, $categories, $filterType);
    }

    /**
     * @throws DateMalformedStringException
     */
    public function getWeeklyReport(?array $categories = null, ?TransactionType $filterType = null): array
    {
        $now = new DateTimeImmutable();
        $startDate = $now->modify('monday this week')->setTime(0, 0);
        $endDate = $now->modify('sunday this week')->setTime(23, 59, 59);

        return $this->buildReport($startDate, $endDate, $categories, $filterType);
    }

    public function getDailyReport(?array $categories = null, ?TransactionType $filterType = null): array
    {
        $startDate = new DateTimeImmutable('today 00:00:00');
        $endDate = new DateTimeImmutable('today 23:59:59');

        return $this->buildReport($startDate, $endDate, $categories, $filterType);
    }

    public function getAllTimeReport(?array $categories = null, ?TransactionType $filterType = null): array
    {
        return $this->buildReport(null, null, $categories, $filterType);
    }

    private function buildReport(
        ?DateTimeImmutable $startDate,
        ?DateTimeImmutable $endDate,
        ?array $categories,
        ?TransactionType $filterType
    ): array {
        $user = $this->userProvider->getUser();

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
    ): float {
        if ($filterType !== null && $filterType !== $type) {
            return 0.0;
        }

        $total = $this->transactionRepository->getTotalByPeriodAndType($user, $type, $startDate, $endDate, $categories);

        return $type === TransactionType::EXPENSE ? abs($total) : $total;
    }
}
