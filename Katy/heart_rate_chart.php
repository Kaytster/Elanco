<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heart Rate Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Heart Rate</h2>
<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="heartRateChart"></canvas>
</div>
<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const heartRates = data.map(d => d.heart_rate);
        createLineChart(document.getElementById('heartRateChart').getContext('2d'), 'Heart Rate (bpm)', hours, heartRates, 'red');
    });
</script>

</body>
</html>
