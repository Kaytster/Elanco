<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Weight Over Time</h2>

<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="weightChart"></canvas>
</div>
<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const weight = data.map(d => d.weight);

        createLineChart(
            document.getElementById('weightChart').getContext('2d'),
            'Weight (kg)',
            hours,
            weight,
            'brown'
        );
    });
</script>

</body>
</html>
