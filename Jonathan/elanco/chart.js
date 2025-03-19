function createDoughnutChart(ctx, label, labels, data, colors) {
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: colors
            }]
        },
        options: {
            responsive: true
        }
    });
}


function createLineChart(ctx, label, labels, data, color) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: color,
                backgroundColor: color + '33',
                borderWidth: 2,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Hour' } },
                y: { title: { display: true, text: label } }
            }
        }
    });
}

function createBarChart(ctx, label, labels, data, color) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: color,
                borderColor: color,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: { title: { display: true, text: 'Hour' } },
                y: { title: { display: true, text: label } }
            }
        }
    });
}
