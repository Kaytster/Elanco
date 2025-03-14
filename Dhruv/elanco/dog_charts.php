<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Activity Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Dog Activity Level</h2>
<canvas id="activityChart"></canvas>

<h2>Dog Heart Rate</h2>
<canvas id="heartRateChart"></canvas>

<h2>Dog Calorie Burn</h2>
<canvas id="calorieChart"></canvas>

<script>
    // Fetch data from PHP
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const steps = data.map(d => d.activity_level);
        const heartRates = data.map(d => d.heart_rate);
        const calorieBurns = data.map(d => d.calorie_burn);

        function createLineChart(ctx, label, data, color) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hours,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: color,
                        backgroundColor: color + '33',
                        borderWidth: 2,
                        fill: true
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

        function createBarChart(ctx, label, data, color) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: hours,
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

        createBarChart(document.getElementById('activityChart').getContext('2d'), 'Activity Level (Steps)', steps, 'blue');
        createLineChart(document.getElementById('heartRateChart').getContext('2d'), 'Heart Rate (bpm)', heartRates, 'red');
        createLineChart(document.getElementById('calorieChart').getContext('2d'), 'Calorie Burn', calorieBurns, 'green');
    });
</script>

</body>
</html>
