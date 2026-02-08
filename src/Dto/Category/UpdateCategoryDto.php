<?php

namespace App\Dto\Category;

use App\Validator\Constraint\Category\CategoryColor;
use App\Validator\Constraint\Category\CategoryIcon;
use App\Validator\Constraint\Category\CategoryName;

class UpdateCategoryDto
{
    public function __construct(
        #[CategoryName]
        public string $name,

        #[CategoryColor]
        public ?string $color = null,

        #[CategoryIcon]
        public ?string $icon = null,
    ) {}
}
