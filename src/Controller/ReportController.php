<?php

namespace App\Controller;

use App\Dto\Report\AllTimeDto;
use App\Dto\Report\DailyDto;
use App\Dto\Report\MonthlyDto;
use App\Dto\Report\WeeklyDto;
use App\Dto\Report\YearlyDto;
use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/reports')]
class ReportController extends AbstractController
{
    #[Route('/monthly', name: 'app_report_monthly', methods: ['GET'])]
    public function monthlyReport(
        #[MapQueryString] MonthlyDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getMonthlyReport($dto->month, $dto->year, $dto->categories, $dto->filterType);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/yearly', name: 'app_report_yearly', methods: ['GET'])]
    public function yearlyReport(
        #[MapQueryString] YearlyDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getYearlyReport($dto->year, $dto->categories, $dto->filterType);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/weekly', name: 'app_report_weekly', methods: ['GET'])]
    public function weeklyReport(
        #[MapQueryString] WeeklyDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getWeeklyReport($dto->categories, $dto->filterType);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/daily', name: 'app_report_daily', methods: ['GET'])]
    public function dailyReport(
        #[MapQueryString] DailyDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getDailyReport($dto->categories, $dto->filterType);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/all-time', name: 'app_report_all_time', methods: ['GET'])]
    public function allTimeReport(
        #[MapQueryString] AllTimeDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getAllTimeReport($dto->categories, $dto->filterType);

        return $this->json($report, Response::HTTP_OK);
    }
}
