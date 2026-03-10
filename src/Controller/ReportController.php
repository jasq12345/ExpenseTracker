<?php

namespace App\Controller;

use App\Dto\Report\DailyDto;
use App\Dto\Report\MonthlyDto;
use App\Dto\Report\WeeklyDto;
use App\Dto\Report\YearlyDto;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reports')]
class ReportController extends AbstractController
{
    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/monthly', name: 'app_report_monthly', methods: ['GET'])]
    public function monthlyReport(
        #[MapRequestPayload] MonthlyDto $dto,
        ReportService $service
    ): JsonResponse
    {
        $report = $service->getMonthlyReport($dto->month, $dto->year, $dto->categories, $dto->filterType);

        return $this->json($report);
    }

    #[Route('/yearly', name: 'app_report_yearly', methods: ['GET'])]
    public function yearlyReport(
        #[MapRequestPayload] YearlyDto $dto,
        ReportService $service
    ): JsonResponse
    {
        $report = $service->getYearlyReport($dto->year, $dto->categories, $dto->filterType);

        return $this->json($report);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route('/weekly', name: 'app_report_weekly', methods: ['GET'])]
    public function weeklyReport(
        #[MapRequestPayload] WeeklyDto $dto,
        ReportService $service
    ): JsonResponse
    {
        $report = $service->getWeeklyReport($dto->categories, $dto->filterType);

        return $this->json($report);
    }

    #[Route('/daily', name: 'app_report_daily', methods: ['GET'])]
    public function dailyReport(
        #[MapRequestPayload] DailyDto $dto,
        ReportService $service
    ): JsonResponse
    {
        $report = $service->getDailyReport($dto->categories, $dto->filterType);

        return $this->json($report);
    }
}
