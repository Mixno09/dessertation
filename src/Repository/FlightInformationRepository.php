<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;

final class FlightInformationRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * FlightInformationRepository constructor.
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findRndLeft(int $id): array
    {
        $sql = 'SELECT rnd_left, time FROM flight_information WHERE departure_id = ? AND  alfa_rud_left > ? AND rnd_left > ? ORDER BY time ASC';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->bindValue(2, 67);
        $statement->bindValue(3, 90);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findRndRight(int $id): array
    {
        $sql = 'SELECT rnd_right, time FROM flight_information WHERE departure_id = ? AND  alfa_rud_right > ? AND rnd_right > ? ORDER BY time ASC';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->bindValue(2, 67);
        $statement->bindValue(3, 90);
        $statement->execute();
        return $statement->fetchAll();
    }
}