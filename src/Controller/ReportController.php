<?php

namespace App\Controller;

use App\Dto\Report\MonthlyDto;
use App\Dto\Report\ReportFilterDto;
use App\Dto\Report\YearlyDto;
use App\Service\ReportService;
use DateMalformedStringException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/reports')]
class ReportController extends AbstractController
{
    /**
     * @throws DateMalformedStringException
     */
    #[Route('/monthly', name: 'app_report_monthly', methods: ['GET'])]
    public function monthlyReport(
        #[MapQueryString] MonthlyDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getMonthlyReport($dto);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/yearly', name: 'app_report_yearly', methods: ['GET'])]
    public function yearlyReport(
        #[MapQueryString] YearlyDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getYearlyReport($dto);

        return $this->json($report, Response::HTTP_OK);
    }

    /**
     * @throws DateMalformedStringException
     */
    #[Route('/weekly', name: 'app_report_weekly', methods: ['GET'])]
    public function weeklyReport(
        #[MapQueryString] ReportFilterDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getWeeklyReport($dto);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/daily', name: 'app_report_daily', methods: ['GET'])]
    public function dailyReport(
        #[MapQueryString] ReportFilterDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getDailyReport($dto);

        return $this->json($report, Response::HTTP_OK);
    }

    #[Route('/all-time', name: 'app_report_all_time', methods: ['GET'])]
    public function allTimeReport(
        #[MapQueryString] ReportFilterDto $dto,
        ReportService $service
    ): JsonResponse {
        $report = $service->getAllTimeReport($dto);

        return $this->json($report, Response::HTTP_OK);
    }
}
