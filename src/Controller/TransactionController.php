<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TransactionController extends AbstractController
{
    protected function getReadGroup(): string
    {
        return 'transaction:read';
    }

    #[Route('', methods: ['GET'])]
    public function list(TransactionRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            ['groups' => ['category:read']]
        );
    }
}
