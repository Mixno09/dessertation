<?php

declare(strict_types=1);

namespace App\Fixture;

use Doctrine\DBAL\Connection;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;

class FlightInformationFixture extends AbstractFixture implements FixtureInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function load(array $options): void
    {
        $sql = file_get_contents(__DIR__ . '/flight_information.sql');
        $this->connection->executeQuery($sql);
    }

    public function getName(): string
    {
        return 'flight_information';
    }
}