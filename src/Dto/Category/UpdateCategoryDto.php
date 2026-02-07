<?php

namespace App\Dto\Category;

class UpdateCategoryDto
{
    public function __construct(
        public string $name,
        public ?string $color = null,
        public ?string $icon = null,
    ){}
}
