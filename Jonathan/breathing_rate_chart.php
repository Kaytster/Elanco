<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breathing Rate Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Breathing Rate</h2>

<div style="width: 300%; max-width: 900px; margin: auto;">
    <canvas id="breathingRateChart"></canvas>
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
                    backgroundColor: 'rgba(0, 0, 0, 0)',
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

    function checkBreathingRate(breathingRates, hours) {
        const highThreshold = 40;
        const lowThreshold = 10;
        
        breathingRates.forEach((rate, index) => {
            if (rate > highThreshold || rate < lowThreshold) {
                Swal.fire({
                    title: 'Breathing Rate Alert!',
                    text: `Breathing Rate detected at ${hours[index]}:00 - ${rate} breaths/min`,
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);
        const breathingRates = data.map(d => d.Breath_Rate);
        
        createLineChart(document.getElementById('breathingRateChart').getContext('2d'), 'Breathing Rate (breaths/min)', hours, breathingRates, 'purple');
        checkBreathingRate(breathingRates, hours);
    });
</script>

</body>
</html>
