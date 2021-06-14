function dd_chart(element, rowsCurrent, rowsEnquiry, calcEngineValueCurrent, calcEngineValueEnquiry) {
    const x1 = [], y1 = [], z1 = [];
    rowsCurrent.forEach((row) => {
        x1.push(row.t4);
        y1.push(row.rvd);
        z1.push(row.ddT4Rvd);
    });
    const dataCurrent = {
        x: x1,
        y: y1,
        z: z1,
        type: 'scatter3d',
        mode: 'markers',
        name: 'Анализируемая модель',
        marker: {
            size: 5,
            color: '#FA6348',
            line: {
                color: '#FBA394',
                width: 0.5
            },
            opacity: 0.8
        }
    };

    const x2 = [], y2 = [], z2 = [];
    rowsEnquiry.forEach((row) => {
        x2.push(row.t4);
        y2.push(row.rvd);
        z2.push(row.ddT4Rvd);
    });
    const dataEnquiry = {
        x: x2,
        y: y2,
        z: z2,
        type: 'scatter3d',
        mode: 'markers',
        name: 'Эталонная модель',
        marker: {
            size: 5,
            color: '#0089FA',
            line: {
                color: '#00437A',
                width: 0.5
            },
            opacity: 0.8
        }
    };

    const sampleVarianceCurrent = {
        x: [calcEngineValueCurrent.t4],
        y: [calcEngineValueCurrent.rvd],
        z: [0],
        type: 'scatter3d',
        mode: 'markers',
        name: 'Среднее значение математических ожиданий исследуемого',
        marker: {
            size: 7,
            color: '#000000',
            line: {
                color: '#000000',
                width: 0.5
            },
            opacity: 0.8
        }
    };

    var sampleVarianceEnquiry = {
        x: [calcEngineValueEnquiry.t4],
        y: [calcEngineValueEnquiry.rvd],
        z: [0],
        type: 'scatter3d',
        mode: 'markers',
        name: 'Среднее значение математических ожиданий эталонного',
        marker: {
        size: 7,
            color: '#FAF200',
            line: {
                color: '#FAF200',
                width: 0.5
            },
            opacity: 0.8
        }
    };

    const layout = {
        scene: {
            camera: {
                eye: {x: -1.5, y: -1.5, z: 1.5},
                center: {x: 0, y: 0, z: -0.15}
            },
            xaxis: {
                title: 'Температура, °C'
            },
            yaxis: {
                title: 'Обороты РВД, %',
            },
            zaxis: {
                title: 'Плотность распределения',
            }
        },
        autosize: true,
        margin: {l: 0, r: 0, b: 0, t: 0},
        legend: {
            x: 0.95,
            y: 0.95,
            xanchor: 'right'
        }
    };

    Plotly.newPlot(
        element,
        [dataCurrent, sampleVarianceCurrent, dataEnquiry, sampleVarianceEnquiry],
        layout,
        {displayModeBar: false}
    );
}

const flight_information_chart = (function () {
    let id = 0;
    function next_id() {
        return 'flight_information_chart-' + id++;
    }

    return function (element, time, valueLeft, valueRight, config) {
        const options = {
            series: [
                {
                    name: 'Правый',
                    data: valueRight
                },
                {
                    name: 'Левый',
                    data: valueLeft
                },
            ],
            title: {
                text: config.title,
                align: 'left',
            },
            chart: {
                id: next_id(),
                group: 'flight_information_chart',
                type: 'line',
                height: 180,
                toolbar: {
                    tools: {
                        download: false,
                        pan: false
                    },
                },
            },
            colors: [config.colorRight, config.colorLeft],
            stroke: {
                curve: 'straight',
                width: 1
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                marker: {
                    show: true
                },
                y: {
                    formatter: function (value) {
                        return value + config.unit;
                    }
                }
            },
            grid: {
                clipMarkers: false
            },
            xaxis: {
                type: 'numeric',
                categories: time,
                tooltip: {
                    formatter: function (value) {
                        return value + ' с';
                    }
                },
                tickAmount: 50,
            },
            yaxis: {
                labels: {
                    minWidth: 40
                }
            }
        };

        new ApexCharts(element, options).render();
    }
})();
