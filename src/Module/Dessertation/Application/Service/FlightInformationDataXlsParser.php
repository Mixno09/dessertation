<?php

declare(strict_types=1);

namespace App\Module\Dessertation\Application\Service;

use App\Module\Dessertation\Domain\FlightInformation\FlightInformationData;
use Symfony\Component\HttpFoundation\File\File;

class FlightInformationDataXlsParser
{
    public function parse(File $file): FlightInformationData
    {

    }
}