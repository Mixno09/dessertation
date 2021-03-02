<?php

namespace App\Controller;

use App\Entity\FlightInformation\FlightInformation;
use App\Fetcher\FlightInformationFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartRunOutController extends AbstractController
{
    private FlightInformationFetcher $fetcher;

    public function __construct(FlightInformationFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @Route("/flightinformation/average/{airplane}/left", name="flight_information_chart_average_left", methods={"GET"})
     */
    public function runOutLeft(int $airplane): Response
    {
        $flightInformations = $this->fetcher->getLeftAverageParameterByAirplaneNumber($airplane);

        $averageT4 = [];
        $averageRnd = [];
        $averageRvd = [];
        /** @var FlightInformation $flightInformation */
        foreach ($flightInformations as $flightInformation) {
            $flightNumber = $flightInformation->getFlightInformationId()->getFlightNumber();
            $averageT4[$flightNumber] = $flightInformation->getLeftEngineParameters()->averageParameter()->getT4();
            $averageRnd[$flightNumber] = $flightInformation->getLeftEngineParameters()->averageParameter()->getRnd();
            $averageRvd[$flightNumber] = $flightInformation->getLeftEngineParameters()->averageParameter()->getRvd();
        }
        var_dump($averageT4, $averageRnd, $averageRvd);
        die();
        return $this->render('chart/runout_rotor.twig', [
            'runOutRotor' => $runOutRotor,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД левого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД левого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД левого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РНД левого двигателя',
        ]);
    }

    /**
     * @Route("/flightinformation/average/{airplane}/right", name="flight_information_chart_average_right", methods={"GET"})
     */
    public function runOutRight(int $airplane)
    {

        return $this->render('chart/runout_rotor.twig', [
            'runOutRotor' => $runOutRotor,
            'time_runout_rotor_rnd' => 'Время выбега ротора РНД правого двигателя',
            'time_runout_rotor_rvd' => 'Время выбега ротора РВД правого двигателя',
            'approximation_time_runout_rotor_rnd' => 'Аппроксимированное время выбега ротора РНД правого двигателя',
            'approximation_time_runout_rotor_rvd' => 'Аппроксимированное время выбега ротора РВД правого двигателя',
        ]);
    }

    /**
     * @param FlightInformation[] $flightInformations
     */
    private function createChartJsConfig(array $flightInformations): array
    {
        $flightNumber = [];
        $averageT4 = [];
        $averageRnd = [];
        $averageRvd = [];
        /** @var FlightInformation $flightInformation */
        foreach ($flightInformations as $flightInformation) {
            $flightNumber[] = $flightInformation->getFlightInformationId()->getFlightNumber();
            $averageT4[] = $flightInformation->getLeftEngineParameters()->averageParameter()->getT4();
            $averageRnd[] = $flightInformation->getLeftEngineParameters()->averageParameter()->getRnd();
            $averageRvd[] = $flightInformation->getLeftEngineParameters()->averageParameter()->getRvd();
        }

        return [
            'type' => 'line',
            'data' => [
                'labels' => $flightNumber,
                'datasets' => [[
                    'label' => 'Среднее заначение температуры Т4 на режиме МГ',
                    'backgroundColor' => '#0000ff',
                    'borderColor' => '#0000ff',
                    'data' => $averageT4,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-average-temp-engine',
                ], [
                    'label' => 'Среднее заначение ротора РНД на режиме МГ',
                    'backgroundColor' => '#ff0000',
                    'borderColor' => '#ff0000',
                    'data' => $averageRnd,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-average-rnd-engine',
                ], [
                    'label' => 'Среднее заначение ротора РВД на режиме МГ',
                    'backgroundColor' => '#00ff00',
                    'borderColor' => '#00ff00',
                    'data' => $averageRvd,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-average-rvd-engine',
                ]
            ],
            'options' => [
                'elements' => [
                    'point' => [
                        'radius' => 0,
                    ]
                ],
                'title' => [
                    'display' => true,
                    'text' => 'Средние значения параметров',
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'hover' => [
                    'mode' => 'nearest',
                    'intersect' => true
                ],
                'scales' => [
                    'xAxes' => [
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Номера вылетов',
                            ]
                        ],
                    ],
                    'yAxes' => [
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Температура, °C',
                            ],
                            'id' => 'y-temp-engine',
                            'ticks' => $this->configTicks(1, 100, $t4Right, $t4Left), //todo подумать над масштабом
                        ],
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Положение РУД, °',
                            ],
                            'id' => 'y-alfa-rud',
                            'ticks' => $this->configTicks(2, 10, $alfaRight, $alfaLeft),
                        ],
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Обороты РНД, %',
                            ],
                            'position' => 'right',
                            'id' => 'y-rnd',
                            'ticks' => $this->configTicks(3, 25, $rndRight, $rndLeft),
                        ],
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Обороты РВД, %',
                            ],
                            'position' => 'right',
                            'id' => 'y-rvd',
                            'ticks' => $this->configTicks(4, 25, $rvdRight, $rvdLeft),
                        ],
                    ]
                ]
            ]
        ];
    }
}
