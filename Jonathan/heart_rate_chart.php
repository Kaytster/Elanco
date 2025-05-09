<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heart Rate Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Heart Rate</h2>
<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="heartRateChart"></canvas>
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

    function checkHeartRate(heartRates, hours) {
        const highThreshold = 141; // High heart rate threshold
        const lowThreshold = 60;  // Low heart rate threshold

        heartRates.forEach((rate, index) => {
            if (rate > highThreshold || rate < lowThreshold) {
                Swal.fire({
                    title: 'Heart Rate Alert!',
                    text: `Heart rate detected at ${hours[index]}:00 - ${rate} bpm`,
                    confirmButtonText: 'OK'
                });
            }
        });
    }

    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);
        const heartRates = data.map(d => d.Heart_Rate);

        createLineChart(document.getElementById('heartRateChart').getContext('2d'), 'Heart Rate (bpm)', hours, heartRates, 'red');
        checkHeartRate(heartRates, hours);
    });
</script>

</body>
</html>
