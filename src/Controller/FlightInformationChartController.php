<?php

namespace App\Controller;

use App\Entity\FlightInformation\EngineParameter;
use App\Fetcher\FlightInformationFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartController extends AbstractController
{
    private FlightInformationFetcher $fetcher;

    public function __construct(FlightInformationFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    /**
     * @Route("/flight-informations/{slug}/chart", name="flight_information_chart", methods="GET")
     */
    public function index(string $slug): Response
    {
        $leftEngineParameters = $this->fetcher->getLeftEngineParametersBySlug($slug);
        $rightEngineParameters = $this->fetcher->getRightEngineParametersBySlug($slug);

        return $this->render('chart/index.html.twig', [
            'chartConfig' => $this->createChartJsConfig($leftEngineParameters, $rightEngineParameters),
        ]);
    }

    /**
     * @param EngineParameter[] $leftEngineParameters
     * @param EngineParameter[] $rightEngineParameters
     */
    private function createChartJsConfig(array $leftEngineParameters, array $rightEngineParameters): array
    {
//        {
//            type: 'line',
//            data: {
//            labels: {{ flightInformationChart.labels|json_encode|raw }},
//            datasets: [{
//                label: 'Температура правого двигателя',
//                    backgroundColor: '#0000ff',
//                    borderColor: '#0000ff',
//                    data: {{ flightInformationChart.t4Right|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-temp-engine',
//                }, {
//                label: 'Температура левого двигателя',
//                    backgroundColor: '#ff0000',
//                    borderColor: '#ff0000',
//                    data: {{ flightInformationChart.t4Left|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-temp-engine',
//                }, {
//                label: 'Положение РУД правого двигателя',
//                    backgroundColor: '#00ff00',
//                    borderColor: '#00ff00',
//                    data: {{ flightInformationChart.alfaRight|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-alfa-rud',
//                }, {
//                label: 'Положение РУД левого двигателя',
//                    backgroundColor: '#000000',
//                    borderColor: '#000000',
//                    data: {{ flightInformationChart.alfaLeft|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-alfa-rud',
//                }, {
//                label: 'Обороты РНД правого двигателя',
//                    backgroundColor: '#999900',
//                    data: {{ flightInformationChart.rndRight|json_encode|raw }},
//                    borderColor: '#999900',
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-rnd',
//                }, {
//                label: 'Обороты РНД левого двигателя',
//                    backgroundColor: '#ff8000',
//                    borderColor: '#ff8000',
//                    data: {{ flightInformationChart.rndLeft|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-rnd',
//                }, {
//                label: 'Обороты РВД правого двигателя',
//                    backgroundColor: '#b300b3',
//                    borderColor: '#b300b3',
//                    data: {{ flightInformationChart.rvdRight|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-rvd',
//                }, {
//                label: 'Обороты РВД левого двигателя',
//                    backgroundColor: '#00b38f',
//                    borderColor: '#00b38f',
//                    data: {{ flightInformationChart.rvdLeft|json_encode|raw }},
//                    borderWidth: 1,
//                    fill: false,
//                    yAxisID: 'y-rvd',
//                }]
//            },
//            options: {
//            elements: {
//                point: {
//                    radius: 0
//                    }
//            },
//            title: {
//                display: true,
//                    text: 'Параметры полета'
//                },
//            tooltips: {
//                mode: 'index',
//                    intersect: false,
//                },
//            hover: {
//                mode: 'nearest',
//                    intersect: true
//                },
//            scales: {
//                xAxes: [{
//                    display: true,
//                        scaleLabel: {
//                        display: true,
//                            labelString: 'Время, с'
//                        }
//                    }],
//                    yAxes: [{
//                    display: true,
//                        scaleLabel: {
//                          display: true,
//                            labelString: 'Температура, °C'
//                        },
//                          id: 'y-temp-engine',
//                          ticks: {{ flightInformationChart.t4Ticks|json_encode|raw }}
//                    }, {
//                    display: true,
//                        scaleLabel: {
//                        display: true,
//                            labelString: 'Положение РУД, °'
//                        },
//                        id: 'y-alfa-rud',
//                        ticks: {{ flightInformationChart.alfaRudTicks|json_encode|raw }}
//                    }, {
//                    display: true,
//                        scaleLabel: {
//                        display: true,
//                            labelString: 'Обороты РНД, %'
//                        },
//                        position: 'right',
//                        id: 'y-rnd',
//                        ticks: {{ flightInformationChart.rndTicks|json_encode|raw }}
//                    }, {
//                    display: true,
//                        scaleLabel: {
//                        display: true,
//                            labelString: 'Обороты РВД, %'
//                        },
//                        position: 'right',
//                        id: 'y-rvd',
//                        ticks: {{ flightInformationChart.rvdTicks|json_encode|raw }}
//                    }]
//                }
//        }
//        }
        $time = [];
        $timeRight = [];
        $t4Right = [];
        $alfaRight = [];
        $rvdRight = [];
        $rndRight = [];
        foreach ($rightEngineParameters as $rightEngineParameter) {
            $time[] = $rightEngineParameter->getTime();
            $timeRight = $rightEngineParameter->getTime();
            $t4Right[$timeRight] = $rightEngineParameter->getT4();
            $alfaRight[$timeRight] = $rightEngineParameter->getAlfaRUD();
            $rvdRight[$timeRight] = $rightEngineParameter->getRvd();
            $rndRight[$timeRight] = $rightEngineParameter->getRnd();
        }
        $timeLeft = 0;
        $t4Left = [];
        $alfaLeft = [];
        $rvdLeft = [];
        $rndLeft = [];
        foreach ($leftEngineParameters as $leftEngineParameter) {
            $timeLeft = $leftEngineParameter->getTime();
            $t4Left[$timeLeft] = $leftEngineParameter->getT4();
            $alfaLeft[$timeLeft] = $leftEngineParameter->getAlfaRUD();
            $rvdLeft[$timeLeft] = $leftEngineParameter->getRvd();
            $rndLeft[$timeLeft] = $leftEngineParameter->getRnd();
        }

        $config = [
            'type' => 'line',
            'data' => [
                'labels' => $time,
                'datasets' => [[
                    'label' => 'Температура правого двигателя',
                    'backgroundColor' => '#0000ff',
                    'borderColor' => '#0000ff',
                    'data' => $t4Right,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-temp-engine',
                ], [
                    'label' => 'Температура левого двигателя',
                    'backgroundColor' => '#ff0000',
                    'borderColor' => '#ff0000',
                    'data' => $t4Left,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-temp-engine',
                ], [
                    'label' => 'Положение РУД правого двигателя',
                    'backgroundColor' => '#00ff00',
                    'borderColor' => '#00ff00',
                    'data' => $alfaRight,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-alfa-rud',
                ], [
                    'label' => 'Положение РУД левого двигателя',
                    'backgroundColor' => '#000000',
                    'borderColor' => '#000000',
                    'data' => $alfaLeft,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-alfa-rud',
                ], [
                    'label' => 'Обороты РНД правого двигателя',
                    'backgroundColor' => '#999900',
                    'borderColor' => '#999900',
                    'data' => $rvdRight,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-rnd',
                ], [
                    'label' => 'Обороты РНД левого двигателя',
                    'backgroundColor' => '#ff8000',
                    'borderColor' => '#ff8000',
                    'data' => $rndLeft,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-rnd',
                ], [
                    'label' => 'Обороты РВД правого двигателя',
                    'backgroundColor' => '#b300b3',
                    'borderColor' => '#b300b3',
                    'data' => $rvdRight,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-rvd',
                ], [
                    'label' => 'Обороты РВД левого двигателя',
                    'backgroundColor' => '#00b38f',
                    'borderColor' => '#00b38f',
                    'data' => $rvdLeft,
                    'borderWidth' => 1,
                    'fill' => false,
                    'yAxisID' => 'y-rvd',]
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
                    'text' => 'Параметры полета',
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
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Время, с',
                        ]
                    ],
                    'yAxes' => [
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Температура, °C',
                        ],
                        'id' => 'y-temp-engine',
                        'ticks' => $this->t4Ticks($rightEngineParameters, $leftEngineParameters),
                    ],
                    [
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Положение РУД, °',
                        ],
                        'id' => 'y-alfa-rud',
                        'ticks' => $this->alfaRudTicks($rightEngineParameters, $leftEngineParameters),
                    ],
                    [
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Обороты РНД, %',
                        ],
                        'position' => 'right',
                        'id' => 'y-rnd',
                        'ticks' => $this->rndTicks($rightEngineParameters, $leftEngineParameters),
                    ],
                    [
                        'display' => true,
                        'scaleLabel' => [
                            'display' => true,
                            'labelString' => 'Обороты РВД, %',
                        ],
                        'position' => 'right',
                        'id' => 'y-rvd',
                        'ticks' => $this->rvdTicks($rightEngineParameters, $leftEngineParameters),
                    ]
                ]
            ]
        ];
        return $config;
    }

    /**
     * @param EngineParameter[] $rightEngineParameters
     * @param EngineParameter[] $leftEngineParameters
     */
    private function t4Ticks(array $rightEngineParameters, array $leftEngineParameters): array
    {
        $t4Right = [];
        foreach ($rightEngineParameters as $rightEngineParameter) {
            $t4Right[] = $rightEngineParameter->getT4();
        }
        $t4Left = [];
        foreach ($leftEngineParameters as $leftEngineParameter) {
            $t4Left[] = $leftEngineParameter->getT4();
        }

        $min = 0;
        $max = 0;
        foreach ($t4Right as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($t4Left as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 100) * 100);
        $max = (int)(ceil($max / 100) * 100);
        $scale = $max - $min;
        $min -= $scale * 3;
        return ['min' => $min, 'max' => $max];
    }

    /**
     * @param EngineParameter[] $rightEngineParameters
     * @param EngineParameter[] $leftEngineParameters
     */
    private function alfaRudTicks(array $rightEngineParameters, array $leftEngineParameters): array
    {
        $alfaRight = [];
        foreach ($rightEngineParameters as $rightEngineParameter) {
            $alfaRight[] = $rightEngineParameter->getAlfaRUD();
        }
        $alfaLeft = [];
        foreach ($leftEngineParameters as $leftEngineParameter) {
            $alfaLeft[] = $leftEngineParameter->getAlfaRUD();
        }

        $min = 0;
        $max = 0;
        foreach ($alfaRight as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($alfaLeft as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 10) * 10);
        $max = (int)(ceil($max / 10) * 10);
        $scale = $max - $min;
        $max += $scale;
        $min -= $scale * 2;
        return ['min' => $min, 'max' => $max];
    }

    /**
     * @param EngineParameter[] $rightEngineParameters
     * @param EngineParameter[] $leftEngineParameters
     */
    private function rndTicks(array $rightEngineParameters, array $leftEngineParameters): array
    {
        $rndRight = [];
        foreach ($rightEngineParameters as $rightEngineParameter) {
            $rndRight[] = $rightEngineParameter->getRnd();
        }
        $rndLeft = [];
        foreach ($leftEngineParameters as $leftEngineParameter) {
            $rndLeft[] = $leftEngineParameter->getRnd();
        }

        $min = 0;
        $max = 0;
        foreach ($rndRight as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($rndLeft as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 25) * 25);
        $max = (int)(ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 2;
        $min -= $scale;
        return ['min' => $min, 'max' => $max];
    }

    /**
     * @param EngineParameter[] $rightEngineParameters
     * @param EngineParameter[] $leftEngineParameters
     */
    private function rvdTicks(array $rightEngineParameters, array $leftEngineParameters): array
    {
        $rvdRight = [];
        foreach ($rightEngineParameters as $rightEngineParameter) {
            $rvdRight[] = $rightEngineParameter->getRvd();
        }
        $rvdLeft = [];
        foreach ($leftEngineParameters as $leftEngineParameter) {
            $rvdLeft[] = $leftEngineParameter->getRvd();
        }

        $min = 0;
        $max = 0;
        foreach ($rvdRight as $right) {
            $min = min($right, $min);
            $max = max($right, $max);
        }
        foreach ($rvdLeft as $left) {
            $min = min($left, $min);
            $max = max($left, $max);
        }
        $min = (int)(floor($min / 25) * 25);
        $max = (int)(ceil($max / 25) * 25);
        $scale = $max - $min;
        $max += $scale * 3;
        return ['min' => $min, 'max' => $max];
    }
}
