<?php

declare(strict_types=1);

namespace App\Form;

class RegistrationFormDto
{
    public string $login;
    public string $password;
    public string $repeatPassword; //todo Как валидировать см. RegistrationFormDto?
}