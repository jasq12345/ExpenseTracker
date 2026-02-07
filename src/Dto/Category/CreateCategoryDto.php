<?php

namespace App\Dto\Category;

class CreateCategoryDto
{
    public function __construct(
        public string $name,
        public ?string $color = null,
        public ?string $icon = null,
    ){}
}
