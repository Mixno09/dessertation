<?php

declare(strict_types=1);

namespace App\Fixture;

use Doctrine\DBAL\Connection;
use Sylius\Bundle\FixturesBundle\Listener\AbstractListener;
use Sylius\Bundle\FixturesBundle\Listener\BeforeFixtureListenerInterface;
use Sylius\Bundle\FixturesBundle\Listener\FixtureEvent;

class DeparturePurgerListener extends AbstractListener implements BeforeFixtureListenerInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getName(): string
    {
        return 'departures_purger';
    }

    public function beforeFixture(FixtureEvent $fixtureEvent, array $options): void
    {
        $sql = 'DELETE FROM departures';
        $this->connection->executeQuery($sql);
    }
}