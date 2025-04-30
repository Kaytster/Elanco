<?php
// Get date parameter or set default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
// Get pet_id and dog_id parameters
$petId = isset($_GET['pet_id']) ? $_GET['pet_id'] : 'Snoopy';
$dogId = isset($_GET['dog_id']) ? $_GET['dog_id'] : 'CANINE001';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Breathing Rate Chart</title>
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="chart_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
    <?php include 'navbar.php'; ?>
</head>
<body>
    <div class="container">
        <a href="dashboard.php?date=<?php echo $date; ?>&pet_id=<?php echo $petId; ?>&dog_id=<?php echo $dogId; ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="chart-header">
            <h1 class="page-title">Breathing Rate Chart</h1>
            <div class="date-display">Date: <?php echo date('d-m-Y', strtotime($date)); ?></div>
        </div>

        <div id="chartContainer">
            <canvas id="breathingRateChart"></canvas>
        </div>
        
        <div class="summary-stats">
            <div class="stat-card">
                <h3 class="stat-title">Average</h3>
                <div class="stat-value" id="avgBreathRate">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Maximum</h3>
                <div class="stat-value" id="maxBreathRate">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Minimum</h3>
                <div class="stat-value" id="minBreathRate">-</div>
            </div>
        </div>
    </div>

    <script>
        // Format date for API call
        const queryDate = '<?php echo date('d-m-Y', strtotime($date)); ?>';
        
        // Fetch data from the server
        fetch(`fetch_dog_data.php?date=${queryDate}&dog_id=<?php echo $dogId; ?>`)
        .then(response => response.json())
        .then(data => {
            const hours = data.map(d => d.Hour);
            const breathRates = data.map(d => parseFloat(d.Breath_Rate) || 0);
            
            // Calculate statistics
            const avgBreathRate = (breathRates.reduce((a, b) => a + b, 0) / breathRates.length).toFixed(1);
            const maxBreathRate = Math.max(...breathRates).toFixed(1);
            const minBreathRate = Math.min(...breathRates).toFixed(1);
            
            // Update statistics display
            document.getElementById('avgBreathRate').textContent = avgBreathRate + ' brpm';
            document.getElementById('maxBreathRate').textContent = maxBreathRate + ' brpm';
            document.getElementById('minBreathRate').textContent = minBreathRate + ' brpm';

            // Create chart using the chart.js utility
            createLineChart(
                document.getElementById('breathingRateChart').getContext('2d'),
                'Breathing Rate (brpm)',
                hours,
                breathRates,
                '#0067B1'
            );
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('chartContainer').innerHTML = '<p class="error-message">Error loading chart data. Please try again later.</p>';
        });
    </script>
</body>
</html>
