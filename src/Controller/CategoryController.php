<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class CategoryController extends AbstractController
{
    protected function getReadGroup(): string
    {
        return 'category:read';
    }
}
