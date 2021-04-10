<?php

declare(strict_types=1);

namespace App\Validator;

use App\Form\ImportFlightInformationDto;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ExistsFlightInformation extends Constraint
{
    public string $message = 'Информация о самолете № {{airplaneNumber}}, с вылетом № {{flightNumber}} уже существует. Дата {{flightDate}}.';

    public function validatedBy()
    {
        return ExistsFlightInformationValidator::class;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}