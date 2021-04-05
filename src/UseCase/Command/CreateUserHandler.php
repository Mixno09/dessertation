<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserHandler
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $repository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository)
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(CreateUserCommand $command): void //todo сделать проверку на уникальное имя
    {
        $passwordHash = $this->encodePassword(
            $command->getPassword()
        );
        $user = new User(
            $command->getLogin(),
            $passwordHash
        );
        $this->repository->save($user);
    }

    private function encodePassword(string $password): string
    {
        $user = User::createEmpty();
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}