<?php

declare(strict_types=1);

namespace App\Action;

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Doctrine\DBAL\Connection;

final class HandleRequest
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * HandleRequest constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(Command $command): void
    {
        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open(
            $command->flightInformation->getRealPath()
        );
        $this->connection->transactional(function (Connection $connection) use ($command, $reader) {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    /** @var \Box\Spout\Common\Entity\Cell[] $cells */
                    $cells = $row->getCells();
                    $connection->insert('flight_information', [
                        'airplane' => (int) $command->numberAirplane,
                        'date' => $command->date->format('Y-m-d'),
                        'departure' => (int) $command->numberFlight,
                        'time' => $cells[0]->getValue(),
                        't4_right' => $cells[1]->getValue(),
                        't4_left' => $cells[2]->getValue(),
                        'alfa_rud_left' => $cells[3]->getValue(),
                        'alfa_rud_right' => $cells[4]->getValue(),
                        'rnd_left' => $cells[5]->getValue(),
                        'rvd_left' => $cells[6]->getValue(),
                        'rnd_right' => $cells[7]->getValue(),
                        'rvd_right' => $cells[8]->getValue(),
                    ]);
                }
            }
        });
    }
}