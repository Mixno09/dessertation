<?php

declare(strict_types=1);

namespace App\Form;

use App\Validator\ExistsFlightInformation;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var int
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $airplaneNumber;
    /**
     * @var \DateTimeImmutable
     * @Assert\NotBlank
     */
    public $flightDate;
    /**
     * @var int
     * @Assert\NotBlank
     * @Assert\Type(type="integer")
     */
    public $flightNumber;
    /**
     * @var \Symfony\Component\HttpFoundation\File\File
     * @Assert\NotBlank
     * @Assert\File(
     *     mimeTypes = {"application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"},
     *     mimeTypesMessage = "Загружаемый файл должен быть типа .xls или .xlsx"
     * )
     */
    public $file;
}