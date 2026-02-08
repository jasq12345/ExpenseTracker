<?php

namespace App\Dto\Category;

use Symfony\Component\Validator\Constraints as Assert;

class DeleteCategoryDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $id
    ){}
}
