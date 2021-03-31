<?php

declare(strict_types=1);

namespace App\Entity\User;

use InvalidArgumentException;

class User
{
    private ?int $id;
    private string $login;
    private string $passwordHash;

    public function __construct(string $login, string $passwordHash)
    {
        $this->setLogin($login);
        $this->passwordHash = $passwordHash;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    private function setLogin(string $login): void
    {
        $login = trim($login);

        $length = mb_strlen($login);
        if ($length >= 1 && $length <= 50) {
            $this->login = $login;
        } else {
            throw new InvalidArgumentException('Логин должен быть от 1 до 50 символов');
        }
    }
}
