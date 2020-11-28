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

    public function findCountEngineShutdowns(): array
    {
        $sql = 'SELECT departure FROM departures ORDER BY departure ASC';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        return $statement->fetchAll();
    }

    public function findTimeRndLeftRotor(int $id): array
    {
        $sql = 'SELECT time, rnd_left FROM flight_information WHERE departure_id = ? AND time > ? AND alfa_rud_left < ? ORDER BY time ASC';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->bindValue(2, 250);
        $statement->bindValue(3, 10);
        $statement->execute();
        $values = $statement->fetchAll();

        $valueRndLeft = [];
        $valueTimeRndLeft = [];
        foreach ($values as $value) {
            $valueRndLeft[] = $value['rnd_left'];
            $valueTimeRndLeft[] = $value['time'];
        }

        return $this->sortTimeByParams($valueTimeRndLeft, $valueRndLeft);
    }

    public function findTimeRvdLeftRotor(int $id): array
    {
        $sql = 'SELECT time, rvd_left FROM flight_information WHERE departure_id = ? AND time > ? AND alfa_rud_left < ? ORDER BY time ASC';
        $statement = $this->connection->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->bindValue(2, 250);
        $statement->bindValue(3, 10);
        $statement->execute();
        $values = $statement->fetchAll();

        $valueRvdLeft = [];
        $valueTimeRvdLeft = [];
        foreach ($values as $value) {
            $valueRvdLeft[] = $value['rvd_left'];
            $valueTimeRvdLeft[] = $value['time'];
        }

        return $this->sortTimeByParams($valueTimeRvdLeft, $valueRvdLeft);
    }

    private function sortTimeByParams(array $times, array $values): array
    {
        $timeSort = [];
        if (count($times) === 0) {
            return $timeSort;
        }
        $timeAndValue[] = $values[0];
        for ($i = 1; $i < count($values); $i++) {
            if ($values[$i] >= 10 && $values[$i] < $values[$i - 1]) {
                $timeAndValue[] = $values[$i];
                continue;
            }
            break;
        }
        return $timeAndValue;
    }
}