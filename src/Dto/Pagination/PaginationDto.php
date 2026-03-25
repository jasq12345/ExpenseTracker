<?php

namespace App\Dto\Pagination;

use Symfony\Component\Validator\Constraints as Assert;

readonly class PaginationDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $page = 1,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $limit = 10,
    ) {}
}
