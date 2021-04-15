<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Test\UserCreatedEvent;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUserHandler
{
    private UserPasswordEncoderInterface $passwordEncoder;
    private UserRepository $repository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->repository = $repository;
        $this->passwordEncoder = $passwordEncoder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(CreateUserCommand $command): int
    {
        $hasUser = $this->repository->hasUserByUsername(
            $command->getLogin()
        );
        if ($hasUser) {
            throw new Exception('Пользователь с именем ' . $command->getLogin() . ' уже существует');
        }

        $passwordHash = $this->encodePassword(
            $command->getPassword()
        );

        $user = new User(
            $command->getLogin(),
            $passwordHash
        );

        $this->repository->save($user);

        $event = new UserCreatedEvent($user->getId());
        $this->eventDispatcher->dispatch($event, 'custom.user_created');

        return $user->getId();
    }

    private function encodePassword(string $password): string
    {
        $user = User::createEmpty();
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}