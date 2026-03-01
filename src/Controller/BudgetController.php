<?php

namespace App\Controller;

use App\Repository\BudgetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BudgetController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function list(BudgetRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            ['groups' => ['category:read']]
        );
    }
}
