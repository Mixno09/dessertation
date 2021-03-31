<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class UserFetcher
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findByLogin(string $login): ?User
    {
        return $this->entityManager
            ->createQuery('SELECT u FROM App\Entity\User\User u WHERE u.login = :login')
            ->setParameter('login', $login)
            ->getOneOrNullResult();
    }
}