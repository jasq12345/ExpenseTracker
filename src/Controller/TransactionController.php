<?php

namespace App\Controller;

use App\Dto\Pagination\PaginationDto;
use App\Dto\Transaction\CreateTransactionDto;
use App\Dto\Transaction\UpdateTransactionDto;
use App\Provider\Pagination\TransactionPaginationProvider;
use App\Repository\TransactionRepository;
use App\Service\PaginationResponseBuilderService;
use App\Service\TransactionService;
use App\Service\UserProviderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/transactions')]
final class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_transaction_list', methods: ['GET'])]
    public function list(
        #[MapQueryString] PaginationDto $dto,
        UserProviderService $providerService,
        PaginationResponseBuilderService $builder,
        TransactionPaginationProvider $provider,
    ): JsonResponse
    {
        $user = $providerService->getUser();

        return $this->json(
            $builder->build($dto, $provider, $user),
            Response::HTTP_OK,
            [],
            ['groups' => ['transaction:read']]
        );
    }

    #[Route('/{id}', name: 'app_transaction_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(TransactionRepository $repository, int $id): JsonResponse
    {
        $transaction = $repository->find($id);

        return $this->json(
            $transaction,
            Response::HTTP_OK,
            [],
            ['groups' => ['transaction:read']]
        );
    }

    #[Route('/', name: 'app_transaction_create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] CreateTransactionDto $dto,
        TransactionService $service
    ): JsonResponse
    {
        $transaction = $service->create($dto);

        return $this->json(['message' => 'Transaction created successfully'], 201);
    }

    #[Route('/{id}', name: 'app_transaction_update', requirements: ['id' => '\d+'], methods: ['PUT', 'PATCH'])]
    public function update(
        #[MapRequestPayload] UpdateTransactionDto $dto,
        TransactionService $service,
        TransactionRepository $repository,
        int $id
    ): JsonResponse
    {
        $transaction = $repository->find($id);

        $service->update($transaction, $dto);

        return $this->json(['message' => 'Transaction updated successfully'], 201);
    }
}
