<?php

declare(strict_types=1);

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueUsernameValidator extends ConstraintValidator
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof UniqueUsername) {
            throw new UnexpectedTypeException($constraint, UniqueUsername::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $hasUser = $this->repository->hasUserByUsername($value);

        if ($hasUser) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{username}}', $value)
                ->addViolation();
        }
    }
}