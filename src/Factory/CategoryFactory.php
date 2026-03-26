<?php

namespace App\Factory;

use App\Entity\Category;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<Category>
 */
final class CategoryFactory extends PersistentObjectFactory
{
    #[\Override]
    public static function class(): string
    {
        return Category::class;
    }

    #[\Override]
    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->unique()->words(2, true),
            'color' => self::faker()->hexColor(),
            'icon' => self::faker()->randomElement(['utensils', 'bus', 'cart', 'wallet', 'home']),
            'user' => UserFactory::new(),
        ];
    }
}
