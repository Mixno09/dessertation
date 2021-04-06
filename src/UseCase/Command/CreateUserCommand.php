<?php

declare(strict_types=1);

namespace App\UseCase\Command;

use App\Form\RegistrationFormDto;

class CreateUserCommand
{
    private string $login;
    private string $password;

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function hydrate(RegistrationFormDto $dto): void
    {
        $this->login = $dto->login;
        $this->password = $dto->password;
    }
}