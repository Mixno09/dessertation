<?php

declare(strict_types=1);

namespace App\Entity;

use InvalidArgumentException;
use LogicException;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private ?int $id = null;
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->setUsername($username);
        $this->password = $password;
    }

    public function getId(): int
    {
        if ($this->id === null) {
            throw new LogicException('Идентификатор не сгенерирован. Используйте App\\Repository\\UserRepository::save().');
        }
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
        return $this->username;
    }

    public function eraseCredentials()
    {
    }

    private function setUsername(string $username): void
    {
        $username = trim($username);

        $length = mb_strlen($username);
        if ($length >= 1 && $length <= 50) {
            $this->username = $username;
        } else {
            throw new InvalidArgumentException('Логин должен быть от 1 до 50 символов');
        }
    }

    public static function createEmpty(): self
    {
        return new self('empty', '');
    }
}