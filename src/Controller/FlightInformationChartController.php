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
//                        display: true,
//                            labelString: 'Температура, °C'
//                        },
//                        id: 'y-temp-engine',
//                        ticks: {{ flightInformationChart.t4Ticks|json_encode|raw }}
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
        $config = ['type' => 'line',]; //todo доделать конфиг
    }
}
