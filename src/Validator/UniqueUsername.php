<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUsername extends Constraint
{
    public string $message = 'Пользователь с именем {{username}} уже существует.';

    public function validatedBy()
    {
        return UniqueUsernameValidator::class;
    }
}