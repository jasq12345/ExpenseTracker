<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Service\TransactionService;
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
            ['groups' => ['transaction:read']]
        );
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getOne(TransactionRepository $repository, int $id): JsonResponse
    {
        $transaction = $repository->find($id);

        return $this->json(
            $transaction,
            Response::HTTP_OK,
            [],
            ['groups' => ['transaction:read']]
        );
    }
}
