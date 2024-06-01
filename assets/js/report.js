import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {
    if (typeof chartContent === 'undefined') {
        return;
    }

    let labelList = [];
    let valueList = [];
    let backgroundColorList = [];
    let borderColorList = [];
    for (const [key, value] of Object.entries(chartContent)) {
        labelList.push(key);
        valueList.push(value);
        backgroundColorList.push(randomRgba(null, null, null, 0.2));
        borderColorList.push(randomRgba(null, null, null, 1));
    }

    let ctx = document.getElementById('traffic-report-chart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labelList,
            datasets: [{
                label: 'Entrance amount',//TODO translate
                data: valueList,
                backgroundColor: backgroundColorList,
                borderColor: borderColorList,
                borderWidth: 1,
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false,
                },
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }],
            }
        }
    });
});



function randomRgba(r = null, g = null, b = null, a = null) {//TODO move to base
    let round = Math.round;
    let rand = Math.random;
    let s = 255;
    return 'rgba(' +
        (r === null ? round(rand() * s) : r) + ',' +
        (g === null ? round(rand() * s) : g) + ',' +
        (b === null ? round(rand() * s) : b) + ',' +
        (a === null ? rand().toFixed(1) : a) +
        ')';
}
