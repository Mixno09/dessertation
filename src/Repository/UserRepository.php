<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findUserById(int $id): ?User
    {
        return $this->entityManager
            ->createQuery('SELECT u FROM App\Entity\User u WHERE u.id = :id')
            ->setParameter('id', $id, Types::INTEGER)
            ->getOneOrNullResult();
    }

    public function findUserByUsername(string $username): ?User
    {
        $username = trim($username);

        return $this->entityManager
            ->createQuery('SELECT u FROM App\Entity\User u WHERE u.username = :username')
            ->setParameter('username', $username, Types::STRING)
            ->getOneOrNullResult();
    }
}
