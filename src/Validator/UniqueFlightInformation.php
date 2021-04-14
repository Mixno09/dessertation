<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueFlightInformation extends Constraint
{
    public string $message = 'Информация о самолете № {{airplaneNumber}}, с вылетом № {{flightNumber}} уже существует. Дата {{flightDate}}.';
    public ?string $airplaneNumberPath = null;
    public ?string $flightNumberPath = null;
    public ?string $flightDatePath = null;
    public ?string $errorPath = null;

    public function validatedBy()
    {
        return UniqueFlightInformationValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}