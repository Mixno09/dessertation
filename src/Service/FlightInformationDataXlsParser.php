<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\FlightInformationPoint;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Symfony\Component\HttpFoundation\File\File;

class FlightInformationDataXlsParser
{
    public function parse(File $file): array
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->getRealPath());
        $flightInformationDataPoint = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                /** @var \Box\Spout\Common\Entity\Cell[] $cells */
                $cells = $row->getCells();
                $time = $cells[0]->getValue();
                $t4left = $cells[1]->getValue();
                $t4Right = $cells[2]->getValue();
                $alfaRudLeft = $cells[3]->getValue();
                $alfaRudRight = $cells[4]->getValue();
                $rndLeft = $cells[5]->getValue();
                $rndRight = $cells[6]->getValue();
                $rvdLeft = $cells[7]->getValue();
                $rvdRight = $cells[8]->getValue();
                $flightInformationDataPoint[] = new FlightInformationPoint(
                    $time,
                    $t4left,
                    $t4Right,
                    $alfaRudLeft,
                    $alfaRudRight,
                    $rndLeft,
                    $rndRight,
                    $rvdLeft,
                    $rvdRight
                );
            }
        }
        return $flightInformationDataPoint;
    }
}