<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

class AirplaneRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getCount(): int
    {
        $statement = $this->connection->executeQuery('SELECT count(DISTINCT airplane) FROM t_departures');
        return (int) $statement->fetchColumn();
    }

    public function getItems(int $offset, int $limit): array
    {
        $statement = $this->connection->executeQuery(
            'SELECT DISTINCT airplane FROM t_departures ORDER BY airplane LIMIT :limit OFFSET :offset',
            [
                'offset' => $offset,
                'limit' => $limit,
            ],
            [
                'offset' => Types::INTEGER,
                'limit' => Types::INTEGER,
            ]
        );
        return $statement->fetchAll();
    }

    public function findAirplanesById(int $airplaneId): array
    {
        return $this->connection->fetchAll(
            'SELECT * FROM t_departures WHERE airplane = :airplane ORDER BY date',
            ['airplane' => $airplaneId]
        );
    }
}