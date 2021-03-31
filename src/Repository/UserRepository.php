<?php

namespace App\Repository;

use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush(); //todo заменить на транзакцию
    }
}
