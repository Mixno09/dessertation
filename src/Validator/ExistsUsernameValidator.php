<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ExistsUsernameValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ExistsUsername) {
            throw new UnexpectedTypeException($constraint, ExistsUsername::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (! is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        /** @var ?User $user */
        $user = $this->entityManager //todo использовать repository
            ->createQuery('SELECT u FROM App\Entity\User u WHERE u.login = :login')
            ->setParameter('login', $value)
            ->getOneOrNullResult();

        if ($value === $user->getUsername()) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('{{username}}', $value)
                ->addViolation();
        }
    }
}