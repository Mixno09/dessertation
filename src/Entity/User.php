<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private ?int $id;
    private string $login;
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->setLogin($login);
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoles()
    {
        return [];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->login;
    }

    public function eraseCredentials()
    {
        $this->password = '';
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

    public static function createEmpty(): self
    {
        return new self('empty', '');
    }
}