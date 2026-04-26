<?php

namespace App\Service;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Entity\User;
use App\Provider\Pagination\PaginatedProviderInterface;

readonly final class PaginationResponseBuilderService
{
    public function build(
        PaginatedDtoInterface $dto,
        PaginatedProviderInterface $provider,
        User $user,
    ): array
    {
        $items = $provider->items($user, $dto);
        $totalItems = $provider->total($user);
        $totalPages = ceil($totalItems / $dto->limit);

        $hasPrev = $dto->page > 1;
        $hasNext = $dto->page < $totalPages;

        $prevPage = $hasPrev ? $dto->page - 1 : null;
        $nextPage = $hasNext ? $dto->page + 1 : null;

        return [
            'data' => $items,
            'meta' => [
                'total_items' => $totalItems,
                'count' => count($items),
                'per_page' => $dto->limit,
                'current_page' => $dto->page,
                'total_pages' => $totalPages,
                'has_prev' => $hasPrev,
                'has_next' => $hasNext,
                'prev_page' => $prevPage,
                'next_page' => $nextPage,
            ],
        ];
    }
}
