<?php

namespace App\Controller;

use App\Entity\FlightInformation\AverageEngineParameter;
use App\Entity\FlightInformation\FlightInformation;
use App\Fetcher\AirplaneFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartAverage extends AbstractController
{
    private AirplaneFetcher $fetcher;

    public function __construct(AirplaneFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @Route("/airplane/average/{airplane}/left", name="airplane_average_left", methods={"GET"})
     */
    public function averageLeft(int $airplane): Response
    {
        $flightInformationList = $this->fetcher->getItemsWithLeftEngineParametersByAirplaneNumber($airplane);

        $flightNumber = [];
        $averageT4 = [];
        $averageRnd = [];
        $averageRvd = [];
        $errors = [];
        foreach ($flightInformationList as $flightInformation) {
            $averageParameter = $flightInformation->getLeftEngineParameters()->averageParameter();
            $flightInformationId = $flightInformation->getFlightInformationId();
            if (! $averageParameter instanceof AverageEngineParameter) { //todo доделать проверку остальных параметров
                $errors[] = 'Проверь самолет с номером ' . $flightInformationId->getAirplaneNumber() . ' и вылетом номер ' . $flightInformationId->getFlightNumber() . ' на целостность данных';
                continue;
            }
            $flightNumber[] = $flightInformationId->getFlightNumber();
            $averageT4[] = $averageParameter->getT4();
            $averageRnd[] = $averageParameter->getRnd();
            $averageRvd[] = $averageParameter->getRvd();
        }

        return $this->render('chart/average.html.twig', [
            'average' => $this->createChartJsConfigForAverage($flightNumber, $averageT4, $averageRnd, $averageRvd),
            't4Rnd' => $this->createChartJsConfigForT4Rnd($averageT4, $averageRnd),
            't4Rvd' => $this->createChartJsConfigForT4Rvd($averageT4, $averageRvd),
            'errors' => $errors,
        ]);
    }

    /**
     * @Route("/airplane/average/{airplane}/right", name="airplane_average_right", methods={"GET"})
     */
    public function averageRight(int $airplane)
    {
        $flightInformationList = $this->fetcher->getItemsWithRightEngineParametersByAirplaneNumber($airplane);

        $flightNumber = [];
        $averageT4 = [];
        $averageRnd = [];
        $averageRvd = [];
        $errors = [];
        foreach ($flightInformationList as $flightInformation) {
            if ($flightInformation->getRightEngineParameters()->averageParameter()->getT4() === null) { //todo доделать проверку остальных параметров
                $errors[] = 'Проверь самолет с номером ' . $flightInformation->getFlightInformationId()->getAirplaneNumber() . ' и вылетом номер ' . $flightInformation->getFlightInformationId()->getFlightNumber() . ' на целостность данных';
                continue;
            }
            $flightNumber[] = $flightInformation->getFlightInformationId()->getFlightNumber();
            $averageT4[] = $flightInformation->getRightEngineParameters()->averageParameter()->getT4();
            $averageRnd[] = $flightInformation->getRightEngineParameters()->averageParameter()->getRnd();
            $averageRvd[] = $flightInformation->getRightEngineParameters()->averageParameter()->getRvd();
        }

        return $this->render('chart/average.html.twig', [
            'average' => $this->createChartJsConfigForAverage($flightNumber, $averageT4, $averageRnd, $averageRvd),
            't4Rnd' => $this->createChartJsConfigForT4Rnd($averageT4, $averageRnd),
            't4Rvd' => $this->createChartJsConfigForT4Rvd($averageT4, $averageRvd),
            'errors' => $errors,
        ]);
    }

    private function createChartJsConfigForAverage(array $flightNumber, array $averageT4, array $averageRnd, array $averageRvd): array
    {
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
                    'yAxisID' => 'y-average-rvd-engine',]
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
                                'labelString' => 'Номер вылета',
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
                            'id' => 'y-average-temp-engine',
                            'ticks' => $this->configTicks(1, 100, $averageT4),
                        ],
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Обороты РНД, %',
                            ],
                            'id' => 'y-average-rnd-engine',
                            'ticks' => $this->configTicks(2, 1, $averageRnd),
                        ],
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Обороты РВД, %',
                            ],
                            'position' => 'right',
                            'id' => 'y-average-rvd-engine',
                            'ticks' => $this->configTicks(3, 1, $averageRvd),
                        ],
                    ]
                ]
            ]
        ];
    }

    private function createChartJsConfigForT4Rnd(array $averageT4, array $averageRnd): array
    {
        $data = array_map( //todo сделать тоже в других методах
            static fn($t4, $rnd) => ['x' => $t4, 'y' => $rnd, 'flightDate' => (new \DateTimeImmutable())->format(\DateTimeImmutable::ISO8601), 'flightNumber' => 199999],
            $averageT4,
            $averageRnd
        );

        return [
            'type' => 'scatter',
            'data' => [
                'datasets' => [[
                    'label' => 'Зависимость Т4 от РНД',
                    'backgroundColor' => '#0000ff',
                    'borderColor' => '#0000ff',
                    'data' => $data
                ]]
            ],
            'options' => [
                'elements' => [
                    'point' => [
                        'radius' => 2,
                    ]
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'scales' => [
                    'xAxes' => [[
                        'type' => 'linear',
                        'position' => 'bottom',
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Средняя температура, °C на режиме МГ',
                        ],
                        'ticks' => ['min' => 150, 'max' => 600],
                    ]],
                    'yAxes' =>
                        [[
                            'type' => 'linear',
                            'position' => 'bottom',
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Средние обороты РНД на режиме МГ',
                            ],
                            'ticks' => ['min' => 20, 'max' => 50],
                        ]]
                ]
            ]];
    }

    private function createChartJsConfigForT4Rvd(array $averageT4, array $averageRvd): array
    {
        $data = array_map(function ($x, $y) {return ['x' => $x, 'y' => $y];}, $averageT4, $averageRvd);

        return [
            'type' => 'scatter',
            'data' => [
                'datasets' => [[
                    'label' => 'Зависимость Т4 от РВД',
                    'backgroundColor' => '#ff0000',
                    'borderColor' => '#ff0000',
                    'data' => $data
                ]]
            ],
            'options' => [
                'elements' => [
                    'point' => [
                        'radius' => 2,
                    ]
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'scales' => [
                    'xAxes' => [[
                        'type' => 'linear',
                        'position' => 'bottom',
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Средняя температура, °C на режиме МГ',
                        ],
                        'ticks' => ['min' => 150, 'max' => 600],
                    ]],
                    'yAxes' =>
                        [[
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Средние обороты РВД на режиме МГ',
                            ],
                            'ticks' => ['min' => 20, 'max' => 70],
                        ]]
                ]
            ]];
    }

    public function createChartJsConfigForRndRvd(array $averageRnd, array $averageRvd): array
    {
        $data = array_map(function ($x, $y) {return ['x' => $x, 'y' => $y];}, $averageRnd, $averageRvd);

        return [
            'type' => 'scatter',
            'data' => [
                'datasets' => [[
                    'label' => 'Зависимость РНД от РВД',
                    'backgroundColor' => '#ff0000',
                    'borderColor' => '#ff0000',
                    'data' => $data
                ]]
            ],
            'options' => [
                'elements' => [
                    'point' => [
                        'radius' => 2,
                    ]
                ],
                'tooltips' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
                'scales' => [
                    'xAxes' => [[
                        'type' => 'linear',
                        'position' => 'bottom',
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Средняя температура, °C на режиме МГ',
                        ],
                        'ticks' => ['min' => 150, 'max' => 600],
                    ]],
                    'yAxes' =>
                        [[
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Средние обороты РВД на режиме МГ',
                            ],
                            'ticks' => ['min' => 20, 'max' => 70],
                        ]]
                ]
            ]];
    }

    /**
     * @param float[] $parameter
     */
    private function configTicks(int $position, int $step, array $parameter): array
    {
        $min = min($parameter);
        $max = max($parameter);
        $min = (int)(floor($min / $step) * $step);
        $max = (int)(ceil($max / $step) * $step);

        $scale = $max - $min;
        $min -= $scale * (3 - $position);
        $max += $scale * ($position - 1);

        return ['min' => $min, 'max' => $max];
    }
}
