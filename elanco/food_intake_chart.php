<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Intake Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Food Intake Over Time</h2>
<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="foodIntakeChart"></canvas>
</div>
<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const foodIntake = data.map(d => d.food_intake);

        createLineChart(
            document.getElementById('foodIntakeChart').getContext('2d'),
            'Food Intake (calories)',
            hours,
            foodIntake,
            'orange'
        );
    });
</script>

</body>
</html>
