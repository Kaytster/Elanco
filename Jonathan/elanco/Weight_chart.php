<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Dog Weight Over Time</h2>

<div style="width: 300%; max-width: 900px; margin: auto;">
    <canvas id="weightChart"></canvas>
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
                            text: 'Weight (kg)'
                        }
                    }
                }
            }
        });
    }

    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);  // Changed to `Hour` to match database field
        const weight = data.map(d => d.Weight);  // Changed to `Weight` to match database field

        createLineChart(
            document.getElementById('weightChart').getContext('2d'),
            'Weight (kg)',
            hours,
            weight,
            'brown'
        );
    })
    .catch(error => console.error('Error fetching data:', error));
</script>

</body>
</html>
