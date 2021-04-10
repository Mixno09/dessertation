<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\FlightInformation\FlightInformation;
use App\Form\ImportFlightInformationDto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
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
        if (!$constraint instanceof ExistsFlightInformation) {
            throw new UnexpectedTypeException($constraint, ExistsFlightInformation::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (! $value instanceof ImportFlightInformationDto) {
            throw new UnexpectedValueException($value, ImportFlightInformationDto::class);
        }


        $repository = $this->entityManager->getRepository(FlightInformation::class); //todo что использовать?

        $flightInformation = $repository->findOneBy([
            'flightInformationId.airplaneNumber' => $value->airplaneNumber,
            'flightInformationId.flightDate' => $value->flightDate,
            'flightInformationId.flightNumber' => $value->flightNumber,
        ]);

        if ($flightInformation instanceof FlightInformation) {
            $this->context->buildViolation($constraint->message)
                ->setParameters([
                    '{{airplaneNumber}}' => $value->airplaneNumber,
                    '{{flightNumber}}' => $value->flightNumber,
                    '{{flightDate}}' => $value->flightDate->format(DATE_ATOM),
                ])
                ->atPath('airplaneNumber')
                ->addViolation();
        }
    }
}