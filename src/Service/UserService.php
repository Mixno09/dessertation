<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Exception\EntityExistsException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $repository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws EntityExistsException
     */
    public function create(CreateUserCommand $command): int
    {
        $user = $this->repository->findUserByUsername(
            $command->getLogin()
        );
        if ($user instanceof User) {
            throw new EntityExistsException(sprintf(
                'Пользователь с именем %s уже существует.',
                $command->getLogin()
            ));
        }

        $passwordHash = $this->encodePassword(
            $command->getPassword()
        );

        $user = new User(
            $command->getLogin(),
            $passwordHash
        );

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user->getId();
    }

    private function encodePassword(string $password): string
    {
        $user = User::createEmpty();
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}