<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User\User as DomainUser;
use App\Fetcher\UserFetcher;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    private UserFetcher $fetcher;

    public function __construct(UserFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function loadUserByUsername(string $username)
    {
        $user = $this->fetcher->findByLogin($username);
        if ($user === null) {
            throw new UsernameNotFoundException();
        }
        return $this->createSecurityUserFromUser($user);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass(string $class)
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    private function createSecurityUserFromUser(DomainUser $user): User
    {
        return new User(
            $user->getLogin(),
            $user->getPasswordHash()
        );
    }
}