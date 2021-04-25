<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\FlightInformation\FlightInformation;
use App\Repository\FlightInformationRepository;
use DateTimeImmutable;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueFlightInformationValidator extends ConstraintValidator
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (! $constraint instanceof UniqueFlightInformation) {
            throw new UnexpectedTypeException($constraint, UniqueFlightInformation::class);
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

        if ($value === null) {
            return;
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $airplaneNumber = $propertyAccessor->getValue($value, $constraint->airplaneNumberPath);
        if ($airplaneNumber === null) {
            return;
        }
        if (! is_int($airplaneNumber)) {
            throw new UnexpectedValueException($airplaneNumber, 'int');
        }
        $flightDate = $propertyAccessor->getValue($value, $constraint->flightDatePath);
        if ($flightDate === null) {
            return;
        }
        if (! $flightDate instanceof DateTimeImmutable) {
            throw new UnexpectedValueException($flightDate, '\DateTimeImmutable');
        }
        $flightNumber = $propertyAccessor->getValue($value, $constraint->flightNumberPath);
        if ($flightNumber === null) {
            return;
        }
        if (! is_int($flightNumber)) {
            throw new UnexpectedValueException($flightNumber, 'int');
        }

        $flightInformation = $this->repository->findFlightInformationByFlightInformationId($airplaneNumber, $flightDate, $flightNumber);

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