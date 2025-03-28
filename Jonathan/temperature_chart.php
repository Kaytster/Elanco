<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temperature Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Temperature</h2>
<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="temperatureChart"></canvas>
</div>
<script>
    function createLineChart(context, label, labels, data, borderColor) {
        new Chart(context, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    borderColor: borderColor,
                    backgroundColor: 'rgba(0, 0, 0, 0)', // Transparent background for line
                    fill: false,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time (Hours)'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: label
                        }
                    }
                }
            }
        });
    }

    function checkTemperature(temperatures, hours) {
        const highThreshold = 34; // High temperature threshold (°C)
        const lowThreshold = 24;  // Low temperature threshold (°C)

        temperatures.forEach((temp, index) => {
            if (temp > highThreshold || temp < lowThreshold) {
                Swal.fire({
                    title: 'Temperature Alert!',
                    text: `Temperature detected at ${hours[index]}:00 - ${temp} °C`,
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);
        const temperatures = data.map(d => d.Temperature);

        createLineChart(document.getElementById('temperatureChart').getContext('2d'), 'Temperature (°C)', hours, temperatures, 'orange');
        checkTemperature(temperatures, hours);
    });
</script>

</body>
</html>
