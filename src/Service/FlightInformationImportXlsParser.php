<?php

declare(strict_types=1);

namespace App\Service;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;

class FlightInformationImportXlsParser
{
    public function parse(File $file): FlightInformationImportXlsParserResult
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($file->getRealPath());

        $time = [];
        $t4Right = [];
        $t4left = [];
        $alfaRudLeft = [];
        $alfaRudRight = [];
        $rndLeft = [];
        $rvdLeft = [];
        $rndRight = [];
        $rvdRight = [];
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                /** @var \Box\Spout\Common\Entity\Cell[] $cells */
                $cells = $row->getCells();
                $value = $cells[0]->getValue();
                $time[] = $value;
                $t4Right[$value] = $cells[1]->getValue();
                $t4left[$value] = $cells[2]->getValue();
                $alfaRudLeft[$value] = $cells[3]->getValue();
                $alfaRudRight[$value] = $cells[4]->getValue();
                $rndLeft[$value] = $cells[5]->getValue();
                $rvdLeft[$value] = $cells[6]->getValue();
                $rndRight[$value] = $cells[7]->getValue();
                $rvdRight[$value] = $cells[8]->getValue();
            }
        }
        return new FlightInformationImportXlsParserResult($time, $t4Right, $t4left, $alfaRudLeft, $alfaRudRight, $rndLeft, $rvdLeft, $rndRight, $rvdRight);
    }
}