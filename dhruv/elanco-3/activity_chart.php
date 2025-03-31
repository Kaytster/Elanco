<?php
// Get date parameter or set default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Level Chart</title>
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="chart.js"></script>
    <?php include 'navbar.php'; ?>
    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .back-link i {
            margin-right: 8px;
        }
        
        .back-link:hover {
            transform: translateX(-5px);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .date-display {
            font-size: 1.2rem;
            color: var(--primary);
            font-weight: 600;
        }
        
        #chartContainer {
            height: 400px;
            padding: 20px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }
        
        .summary-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 180px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px;
            text-align: center;
        }
        
        .stat-title {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php?date=<?php echo $date; ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="chart-header">
            <h1 class="page-title">Activity Level Chart</h1>
            <div class="date-display">Date: <?php echo date('d-m-Y', strtotime($date)); ?></div>
        </div>
        
        <div id="chartContainer">
            <canvas id="activityChart"></canvas>
        </div>
        
        <div class="summary-stats">
            <div class="stat-card">
                <h3 class="stat-title">Average</h3>
                <div class="stat-value" id="avgActivity">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Maximum</h3>
                <div class="stat-value" id="maxActivity">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Minimum</h3>
                <div class="stat-value" id="minActivity">-</div>
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
            const activityLevels = data.map(d => parseInt(d.Activity_Level) || 0);
            
            // Calculate statistics
            const avgActivity = (activityLevels.reduce((a, b) => a + b, 0) / activityLevels.length).toFixed(0);
            const maxActivity = Math.max(...activityLevels);
            const minActivity = Math.min(...activityLevels);
            
            // Update statistics display
            document.getElementById('avgActivity').textContent = avgActivity + ' steps';
            document.getElementById('maxActivity').textContent = maxActivity + ' steps';
            document.getElementById('minActivity').textContent = minActivity + ' steps';

            // Create chart using the chart.js utility
            createLineChart(
                document.getElementById('activityChart').getContext('2d'),
                'Activity Level (steps)',
                hours,
                activityLevels,
                '#06D6A0'
            );
            
            // Check for low activity
            checkActivityLevel(activityLevels, hours);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('chartContainer').innerHTML = '<p class="error-message">Error loading chart data. Please try again later.</p>';
        });
        
        function checkActivityLevel(activityLevels, hours) {
            const lowThreshold = 20; // Low activity threshold

            activityLevels.forEach((level, index) => {
                if (level < lowThreshold) {
                    Swal.fire({
                        title: 'Low Activity Alert!',
                        text: `Low activity detected at ${hours[index]}:00 - Level: ${level}`,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    </script>
</body>
</html>
