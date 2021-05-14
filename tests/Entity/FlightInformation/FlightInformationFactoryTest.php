<?php
/** @noinspection NonAsciiCharacters */

declare(strict_types=1);

namespace App\Tests\Entity\FlightInformation;

use App\Entity\FlightInformation\FlightInformationFactory;
use PHPUnit\Framework\TestCase;

final class FlightInformationFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function расчет_средних_значений_вылета(): void
    {
        $time = [0, 1, 2, 3, 4, 5, 6, 7, 8];
        $t4Right = [0 => 255, 1 => 260, 2 => 258, 3 => 257, 4 => 264, 5 => 251, 6 => 256, 7 => 263, 8 => 259];
        $t4Left = [0 => 255, 1 => 260, 2 => 258, 3 => 257, 4 => 264, 5 => 251, 6 => 256, 7 => 263, 8 => 259];
        $alfaRudLeft = [0 => 31, 1 => 31, 2 => 31, 3 => 31, 4 => 31, 5 => 31, 6 => 31, 7 => 31, 8 => 32];
        $alfaRudRight = [0 => 31, 1 => 31, 2 => 31, 3 => 31, 4 => 31, 5 => 31, 6 => 31, 7 => 31, 8 => 32];
        $rndLeft = [0 => 31, 1 => 29.9, 2 => 31.1, 3 => 34, 4 => 36.5, 5 => 36.4, 6 => 36.6, 7 => 35, 8 => 35.5];
        $rvdLeft = [0 => 48.4, 1 => 48.5, 2 => 48.6, 3 => 48.7, 4 => 48.8, 5 => 49, 6 => 48.9, 7 => 49.1, 8 => 49.2];
        $rndRight = [0 => 31, 1 => 29.9, 2 => 31, 3 => 37, 4 => 36.5, 5 => 36.9, 6 => 36.6, 7 => 36.5, 8 => 36.5];
        $rvdRight = [0 => 48.4, 1 => 48.5, 2 => 48.6, 3 => 48.7, 4 => 48.8, 5 => 49, 6 => 48.9, 7 => 49.1, 8 => 49.2];

        $sut = FlightInformationFactory::create(
            1,
            new \DateTimeImmutable(),
            1,
            $time,
            $t4Right,
            $t4Left,
            $alfaRudLeft,
            $alfaRudRight,
            $rndLeft,
            $rvdLeft,
            $rndRight,
            $rvdRight
        );

        $leftCalcParameter = $sut->getLeftEngineParameters()->calcParameter();
        self::assertSame(34.4, $leftCalcParameter->getRnd());
        self::assertSame(48.92, $leftCalcParameter->getRvd());
        self::assertSame(257.6, $leftCalcParameter->getT4());
        $rightCalcParameter = $sut->getRightEngineParameters()->calcParameter();
        self::assertNull($rightCalcParameter);
    }
}
