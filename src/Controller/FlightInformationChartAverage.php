<?php

namespace App\Controller;

use App\Entity\FlightInformation\EngineParameterCollection;
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
    public function runOutLeft(int $airplane): Response
    {
        $engineParameterCollection = $this->fetcher->getLeftAverageParameterByAirplaneNumber($airplane);
        $flightNumber = $this->fetcher->getFlightNumberByAirplaneNumber($airplane);

        return $this->render('chart/average.html.twig', [
            'average' => $this->createChartJsConfigForAverage($engineParameterCollection, $flightNumber),
            'scatterT4Rnd' => $this->createChartJsConfigForT4Rnd($engineParameterCollection, $flightNumber),
        ]);
    }

    /**
     * @Route("/airplane/average/{airplane}/right", name="airplane_average_right", methods={"GET"})
     */
    public function runOutRight(int $airplane)
    {
        $engineParameterCollection = $this->fetcher->getLeftAverageParameterByAirplaneNumber($airplane);
        $flightNumber = $this->fetcher->getFlightNumberByAirplaneNumber($airplane);

        return $this->render('chart/average.html.twig', [
            'average' => $this->createChartJsConfigForAverage($engineParameterCollection, $flightNumber),
        ]);
    }

    /**
     * @param EngineParameterCollection[] $engineParameterCollections
     */
    private function createChartJsConfigForAverage(array $engineParameterCollections, array $flights): array
    {
        $flightNumber = [];
        foreach ($flights as $flightNumbers) {
            $flightNumber[] = $flightNumbers['flightInformationId.flightNumber'];
        }
        $averageT4 = [];
        $averageRnd = [];
        $averageRvd = [];
        /** @var EngineParameterCollection $engineParameter */
        foreach ($engineParameterCollections as $engineParameterCollection) {
            $averageT4[] = $engineParameterCollection->averageParameter()->getT4();
            $averageRnd[] = $engineParameterCollection->averageParameter()->getRnd();
            $averageRvd[] = $engineParameterCollection->averageParameter()->getRvd();
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

    /**
     * @param EngineParameterCollection[] $engineParameterCollection
     */
    private function createChartJsConfigForT4Rnd(array $engineParameterCollection, array $flight): array
    {
        return [
            'type' => 'scatter',
            'data' => [
                'datasets' => [[
                    'label' => 'Scatter Dataset',
                    'data' => [[
                        'x' => -5,
                        'y' => -5
                    ], [
                        'x' => 0,
                        'y' => 10
                    ], [
                        'x' => 10,
                        'y' => 5
                    ]]
                ]]
            ],
            'options' => [
                'elements' => [
                    'point' => [
                        'radius' => 10,
                    ]
                ],
                'scales' => [
                    'xAxes' => [[
                        'type' => 'linear',
                        'position' => 'bottom'
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
