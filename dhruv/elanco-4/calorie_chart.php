<?php
// Get date parameter or set default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calorie Burn Chart</title>
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="chart_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container">
        <a href="dashboard.php?date=<?php echo $date; ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="chart-header">
            <h1 class="page-title">Calorie Burn Chart</h1>
            <div class="date-display">Date: <?php echo date('d-m-Y', strtotime($date)); ?></div>
        </div>
        
        <div id="chartContainer">
            <canvas id="calorieChart"></canvas>
        </div>
        
        <div class="summary-stats">
            <div class="stat-card">
                <h3 class="stat-title">Total Calories</h3>
                <div class="stat-value" id="totalCalories">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Maximum</h3>
                <div class="stat-value" id="maxCalories">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Average Per Hour</h3>
                <div class="stat-value" id="avgCalories">-</div>
            </div>
        </div>
    </div>

    <script>
        // Format date for API call
        const queryDate = '<?php echo date('d-m-Y', strtotime($date)); ?>';
        
        // Fetch data from the server
        fetch('fetch_dog_data.php?date=' + queryDate)
        .then(response => response.json())
        .then(data => {
            const hours = data.map(d => d.Hour);
            const calorieBurns = data.map(d => parseInt(d.Calorie_Burnt) || 0);
            
            // Calculate statistics
            const totalCalories = calorieBurns.reduce((a, b) => a + b, 0);
            const avgCalories = (totalCalories / calorieBurns.length).toFixed(0);
            const maxCalories = Math.max(...calorieBurns);
            
            // Update statistics display
            document.getElementById('totalCalories').textContent = totalCalories + ' cal';
            document.getElementById('maxCalories').textContent = maxCalories + ' cal';
            document.getElementById('avgCalories').textContent = avgCalories + ' cal';

            // Create chart using the chart.js utility
            createLineChart(
                document.getElementById('calorieChart').getContext('2d'),
                'Calorie Burn (calories)',
                hours,
                calorieBurns,
                '#06D6A0'
            );
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('chartContainer').innerHTML = '<p class="error-message">Error loading chart data. Please try again later.</p>';
        });
    </script>
</body>
</html>
