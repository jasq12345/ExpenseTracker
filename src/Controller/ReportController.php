<?php

namespace App\Controller;

use App\Dto\Report\MonthlyDto;
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
}
