<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breathing Rate Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Breathing Rate</h2>

<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="breathingRateChart"></canvas>
</div>
<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const breathingRates = data.map(d => d.breathing_rate);
        createLineChart(document.getElementById('breathingRateChart').getContext('2d'), 'Breathing Rate (breaths/min)', hours, breathingRates, 'purple');
    });
</script>

</body>
</html>
