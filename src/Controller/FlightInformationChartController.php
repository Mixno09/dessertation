<?php

namespace App\Controller;

use App\Entity\FlightInformation\EngineParameter;
use App\Repository\FlightInformationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FlightInformationChartController extends AbstractController
{
    private FlightInformationRepository $repository;

    public function __construct(FlightInformationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/flight-informations/{slug}/chart", name="flight_information_chart", methods="GET")
     */
    public function index(string $slug): Response
    {
        $leftEngineParameters = $this->repository->findLeftEngineParametersBySlug($slug);
        $rightEngineParameters = $this->repository->findRightEngineParametersBySlug($slug);
        if (count($leftEngineParameters) === 0 || count($rightEngineParameters) === 0) {
            throw $this->createNotFoundException('Данных не существует.');
        }

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
        $time = [];
        $t4Right = [];
        $alfaRight = [];
        $rvdRight = [];
        $rndRight = [];
        foreach ($rightEngineParameters as $rightEngineParameter) {
            $time[] = $rightEngineParameter->getTime();
            $t4Right[] = $rightEngineParameter->getT4();
            $alfaRight[] = $rightEngineParameter->getAlfaRUD();
            $rvdRight[] = $rightEngineParameter->getRvd();
            $rndRight[] = $rightEngineParameter->getRnd();
        }
        $t4Left = [];
        $alfaLeft = [];
        $rvdLeft = [];
        $rndLeft = [];
        foreach ($leftEngineParameters as $leftEngineParameter) {
            $t4Left[] = $leftEngineParameter->getT4();
            $alfaLeft[] = $leftEngineParameter->getAlfaRUD();
            $rvdLeft[] = $leftEngineParameter->getRvd();
            $rndLeft[] = $leftEngineParameter->getRnd();
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
                    'data' => $rndRight,
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
                        [
                            'display' => true,
                            'scaleLabel' => [
                                'display' => true,
                                'labelString' => 'Время, с',
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
                            'ticks' => $this->configTicks(1, 100, $t4Right, $t4Left),
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
        return $config;
    }

    /**
     * @param float[] $rightParameters
     * @param float[] $leftParameters
     */
    private function configTicks(int $position, int $step, array $rightParameters, array $leftParameters): array
    {
        $min = min(
            min($rightParameters),
            min($leftParameters)
        );
        $max = max(
            max($rightParameters),
            max($leftParameters)
        );
        $min = (int)(floor($min / $step) * $step);
        $max = (int)(ceil($max / $step) * $step);

        $scale = $max - $min;
        $min -= $scale * (4 - $position);
        $max += $scale * ($position - 1);

        return ['min' => $min, 'max' => $max];
    }
}
