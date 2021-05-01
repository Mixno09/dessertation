<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class UserProvider implements UserProviderInterface
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function loadUserByUsername(string $username)
    {
        $user = $this->repository->findUserByUsername($username);
        if ($user === null) {
            throw new UsernameNotFoundException();
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (! $user instanceof User) {
            throw new UnsupportedUserException();
        }

        $refreshedUser = $this->repository->findUserByUsername(
            $user->getUsername()
        );
        if ($refreshedUser === null) {
            throw new UsernameNotFoundException();
        }

        return $refreshedUser;
    }

    public function supportsClass(string $class)
    {
        return (User::class === $class);
    }
}