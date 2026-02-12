<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class TransactionController extends AbstractController
{
    protected function getReadGroup(): string
    {
        return 'transaction:read';
    }
}
