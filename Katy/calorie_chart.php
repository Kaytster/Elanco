<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Burn Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Calorie Burn</h2>

<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="calorieChart"></canvas>
</div>
<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const calorieBurns = data.map(d => d.calorie_burn);
        createLineChart(document.getElementById('calorieChart').getContext('2d'), 'Calorie Burn', hours, calorieBurns, 'green');
    });
</script>

</body>
</html>
