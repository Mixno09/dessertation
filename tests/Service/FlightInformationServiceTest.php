<?php
/** @noinspection NonAsciiCharacters */

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\FlightInformation\FlightInformation;
use App\Exception\EntityExistsException;
use App\Repository\FlightInformationRepository;
use App\Service\CreateFlightInformationCommand;
use App\Service\FlightInformationService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class FlightInformationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function нельзя_создать_дубликат_вылета(): void
    {
        $repositoryStub = $this->createStub(FlightInformationRepository::class);
        $repositoryStub
            ->method('findFlightInformationByFlightInformationId')
            ->willReturn(
                $this->createMock(FlightInformation::class)
            );
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $sut = new FlightInformationService($repositoryStub, $entityManagerMock);
        $command = new CreateFlightInformationCommand(
            1,
            new \DateTimeImmutable(),
            1,
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            [],
            []
        );

        $this->expectException(EntityExistsException::class);
        $sut->create($command);
    }
}
