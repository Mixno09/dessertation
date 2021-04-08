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

    public function findOneByUserId(int $id): ?User
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getRepository()->findOneBy(['id' => $id]);
    }

    public function findOneByUsername(string $username): ?User
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->getRepository()->findOneBy(['login' => $username]);
    }

    public function save(UserInterface $user): ?int
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user->getId();
    }

    private function getRepository(): EntityRepository
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->entityManager->getRepository(User::class);
    }
}
