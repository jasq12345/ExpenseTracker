You’re running into a **classic typing problem**:

> Your `PaginationResponseBuilderService` wants one common provider interface, but each provider may need a different DTO once filters are added.

For example:

```php
public function items(User $user, PaginationDto $dto): array
```


works while everything only needs pagination.

But once `TransactionPaginationProvider` needs something like:

```php
ListTransactionDto $dto
```


then this kind of interface becomes hard:

```php
interface PaginatedProviderInterface
{
    public function items(User $user, PaginationDto $dto): array;
}
```


Because `ListTransactionDto` is **not** a `PaginationDto`.

The clean solution is: **don’t make the provider depend directly on `PaginationDto`. Make list/filter DTOs expose pagination.**

---

## Recommended structure

Create a small interface for DTOs that contain pagination:

```php
<?php

namespace App\Dto\Pagination;

interface PaginatedDtoInterface
{
    public function pagination(): PaginationDto;
}
```


Then your filtered DTOs can implement it.

Example:

```php
<?php

namespace App\Dto\Transaction;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Pagination\PaginationDto;

readonly class ListTransactionDto implements PaginatedDtoInterface
{
    public function __construct(
        public PaginationDto $pagination,
        public ?string $type = null,
        public ?int $categoryId = null,
        public ?string $from = null,
        public ?string $to = null,
    ) {}

    public function pagination(): PaginationDto
    {
        return $this->pagination;
    }
}
```


For budgets, if you currently only have pagination, you can either create:

```php
<?php

namespace App\Dto\Budget;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Dto\Pagination\PaginationDto;

readonly class ListBudgetDto implements PaginatedDtoInterface
{
    public function __construct(
        public PaginationDto $pagination,
    ) {}

    public function pagination(): PaginationDto
    {
        return $this->pagination;
    }
}
```


That may feel like “extra DTO”, but it keeps the system consistent and will save pain later.

---

## Provider interface

Then your provider interface can use the shared list DTO contract:

```php
<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Entity\User;

interface PaginatedProviderInterface
{
    public function items(User $user, PaginatedDtoInterface $dto): array;

    public function total(User $user, PaginatedDtoInterface $dto): int;
}
```


Important: `total()` should also receive the DTO, because filters affect total count.

Example: if user filters transactions by category, total should count only that category, not all transactions.

---

## Update response builder

Your `PaginationResponseBuilderService` should not care what filters exist. It only needs pagination metadata.

```php
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
    ): array {
        $pagination = $dto->pagination();

        $items = $provider->items($user, $dto);
        $totalItems = $provider->total($user, $dto);
        $totalPages = (int) ceil($totalItems / $pagination->limit);

        $hasPrev = $pagination->page > 1;
        $hasNext = $pagination->page < $totalPages;

        $prevPage = $hasPrev ? $pagination->page - 1 : null;
        $nextPage = $hasNext ? $pagination->page + 1 : null;

        return [
            'data' => $items,
            'meta' => [
                'total_items' => $totalItems,
                'count' => count($items),
                'per_page' => $pagination->limit,
                'current_page' => $pagination->page,
                'total_pages' => $totalPages,
                'has_prev' => $hasPrev,
                'has_next' => $hasNext,
                'prev_page' => $prevPage,
                'next_page' => $nextPage,
            ],
        ];
    }
}
```


---

## Update provider example

Your budget provider would become:

```php
<?php

namespace App\Provider\Pagination;

use App\Dto\Pagination\PaginatedDtoInterface;
use App\Entity\User;
use App\Repository\BudgetRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

readonly class BudgetPaginationProvider implements PaginatedProviderInterface
{
    public function __construct(
        private BudgetRepository $repository,
    ) {}

    public function items(User $user, PaginatedDtoInterface $dto): array
    {
        try {
            return $this->repository->listByUser($user, $dto->pagination());
        } catch (Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }

    public function total(User $user, PaginatedDtoInterface $dto): int
    {
        try {
            return $this->repository->countByUser($user);
        } catch (Throwable $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }
}
```


For filtered providers, you use the full DTO:

```php
public function items(User $user, PaginatedDtoInterface $dto): array
{
    if (!$dto instanceof ListTransactionDto) {
        throw new InvalidArgumentException('Expected ListTransactionDto.');
    }

    return $this->repository->listByUser($user, $dto);
}
```


But this `instanceof` is a bit ugly.

---

## Even better: make each provider strongly typed internally

Because PHP interfaces cannot express generics natively, the most practical Symfony/PHP approach is:

```php
interface PaginatedProviderInterface
{
    public function items(User $user, PaginatedDtoInterface $dto): array;

    public function total(User $user, PaginatedDtoInterface $dto): int;
}
```


Then in each provider, validate the expected DTO if needed.

Example:

```php
private function assertDto(PaginatedDtoInterface $dto): ListTransactionDto
{
    if (!$dto instanceof ListTransactionDto) {
        throw new InvalidArgumentException(sprintf(
            'Expected %s, got %s.',
            ListTransactionDto::class,
            $dto::class,
        ));
    }

    return $dto;
}
```


Then:

```php
public function items(User $user, PaginatedDtoInterface $dto): array
{
    $dto = $this->assertDto($dto);

    return $this->repository->listByUser($user, $dto);
}
```


This keeps `PaginationResponseBuilderService` clean and keeps each provider responsible for its own DTO type.

---

## Main idea

Do **not** try to make `PaginationDto` the universal DTO.

Instead:

```php
PaginationDto = only page + limit
ListTransactionDto = pagination + filters
ListBudgetDto = pagination + maybe filters later
PaginatedDtoInterface = "this DTO has pagination"
PaginatedProviderInterface = "this provider can build paginated data"
```


That design will scale nicely when you add filters like:

```php
?int $categoryId
?DateTimeImmutable $from
?DateTimeImmutable $to
?string $type
```


And your response builder stays generic.
