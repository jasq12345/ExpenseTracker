<?php

namespace App\Controller;

use App\Dto\Transaction\CreateTransactionDto;
use App\Repository\TransactionRepository;
use App\Service\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

    #[Route('/', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateTransactionDto $dto,
        TransactionService $service
    ): JsonResponse
    {
        $transaction = $service->create($dto);

        return $this->json(
            $transaction,
            Response::HTTP_OK,
            [],
            ['groups' => ['transaction:read']]
        );
    }

    #[Route('/{id}', requirements: ['id' => '\d+'], methods: ['PUT', 'PATCH'])]
    public function update(
        #[MapRequestPayload] UpdateTransactionDto $dto,
        TransactionService $service,
        TransactionRepository $repository,
        int $id
    ): JsonResponse
    {
        $transaction = $repository->find($id);

        $service->update($transaction, $dto);

        return $this->json(
            $transaction,
            Response::HTTP_OK,
            [],
            ['groups' => ['transaction:read']]
        );
    }
}
