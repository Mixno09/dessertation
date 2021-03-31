<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\User\User;
use App\Repository\UserRepository;
use App\Security\User as SecurityUser;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserHandler
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $repository;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->repository = $repository;
    }

    public function handle(CreateUserCommand $command): void
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
        $user = SecurityUser::createEmpty();
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}