<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Level Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Dog Activity Level Over Time</h2>

<div style="width: 300%; max-width: 900px; margin: auto;">
    <canvas id="activityChart"></canvas>
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

    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);  // Ensure matching field name
        const activityLevel = data.map(d => d.Activity_Level);  // Ensure matching field name

        createLineChart(
            document.getElementById('activityChart').getContext('2d'),
            'Activity Level',
            hours,
            activityLevel,
            'blue'
        );
    })
    .catch(error => console.error('Error fetching data:', error));
</script>

</body>
</html>

