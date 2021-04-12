<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\FlightInformation\FlightInformation;
use App\Form\ImportFlightInformationDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class ExistsFlightInformationValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof ExistsFlightInformation) {
            throw new UnexpectedTypeException($constraint, ExistsFlightInformation::class);
        }

        if (! is_string($constraint->airplaneNumberPath)) {
            throw new ConstraintDefinitionException('Нужно заполнить поле airplaneNumberPath');
        }

        if (! is_string($constraint->flightNumberPath)) {
            throw new ConstraintDefinitionException('Нужно заполнить поле flightNumberPath');
        }

        if (! is_string($constraint->flightDatePath)) {
            throw new ConstraintDefinitionException('Нужно заполнить поле flightDatePath');
        }

        if (null === $value) {
            return;
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $airplaneNumber = $propertyAccessor->getValue($value, $constraint->airplaneNumberPath);
        $flightDate = $propertyAccessor->getValue($value, $constraint->flightDatePath); //todo проверить на datetime
        $flightNumber = $propertyAccessor->getValue($value, $constraint->flightNumberPath);

        $repository = $this->entityManager->getRepository(FlightInformation::class); //todo брать фетчер и сделать has
        $flightInformation = $repository->findOneBy([
            'flightInformationId.airplaneNumber' => $airplaneNumber,
            'flightInformationId.flightDate' => $flightDate,
            'flightInformationId.flightNumber' => $flightNumber,
        ]);

        if ($flightInformation instanceof FlightInformation) {
            $errorPath = $constraint->errorPath ?? $constraint->airplaneNumberPath;
            $this->context->buildViolation($constraint->message)
                ->setParameters([
                    '{{airplaneNumber}}' => $airplaneNumber,
                    '{{flightNumber}}' => $flightNumber,
                    '{{flightDate}}' => $flightDate->format('d.m.Y'),
                ])
                ->atPath($errorPath)
                ->addViolation();
        }
    }
}