<?php

namespace App\Factory;

use App\Entity\User;
use Override;
use Zenstruck\Foundry\Persistence\PersistentObjectFactory;

/**
 * @extends PersistentObjectFactory<User>
 */
final class UserFactory extends PersistentObjectFactory
{
    #[Override]
    public static function class(): string
    {
        return User::class;
    }

    #[Override]
    protected function defaults(): array|callable
    {
        return [
            'username' => self::faker()->unique()->userName(),
            'email' => self::faker()->unique()->safeEmail(),
            'password' => password_hash('password', PASSWORD_BCRYPT),
            'roles' => ['ROLE_USER'],
        ];
    }
}
