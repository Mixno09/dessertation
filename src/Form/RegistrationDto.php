<?php

declare(strict_types=1);

namespace App\Form;

use App\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Length(min=1, max=50, allowEmptyString=false)
     * @AppAssert\UniqueUsername()
     */
    public string $login;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="string")
     * @Assert\Length(min=5, allowEmptyString=false)
     */
    public string $password;
    /**
     * @Assert\IdenticalTo(propertyPath="password", message="Пароли не совпадают")
     */
    public string $repeatPassword;
}