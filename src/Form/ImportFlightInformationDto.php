<?php

declare(strict_types=1);

namespace App\Form;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\ExistsFlightInformation;

/**
 * @ExistsFlightInformation(
 *     airplaneNumberPath="airplaneNumber",
 *     flightDatePath="flightDate",
 *     flightNumberPath="flightNumber",
 *     errorPath="airplaneNumber"
 * )
 */
class ImportFlightInformationDto
{
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public int $airplaneNumber;
    /**
     * @Assert\NotBlank
     */
    public DateTimeImmutable $flightDate;
    /**
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public int $flightNumber;
    /**
     * @Assert\NotBlank
     * @Assert\File(
     *     mimeTypes = {"application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"},
     *     mimeTypesMessage = "Загружаемый файл должен быть типа .xls или .xlsx"
     * )
     */
    public File $file;
}