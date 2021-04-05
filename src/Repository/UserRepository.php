<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findOneByUsername($username): ?User
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getRepository()->findOneBy(['login' => $username]);
    }

    public function save(UserInterface $user): void
    {
        $this->entityManager->persist($user); // todo заменить на транзакцию
        $this->entityManager->flush();
    }

    private function getRepository(): EntityRepository
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->entityManager->getRepository(User::class);
    }
}
