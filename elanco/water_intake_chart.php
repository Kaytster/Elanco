<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Intake Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Water Intake</h2>
<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="waterIntakeChart"></canvas>
</div>

<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);
        const waterIntakes = data.map(d => d.Water_Intake);
        createLineChart(document.getElementById('waterIntakeChart').getContext('2d'), 'Water Intake (ml)', hours, waterIntakes, 'blue');
    });
</script>

</body>
</html>
