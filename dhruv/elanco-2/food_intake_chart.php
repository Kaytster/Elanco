<?php
// Get date parameter or set default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Intake Chart</title>
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        
        /* Fix the chart container height */
        #chartContainer {
            height: 400px;
            padding: 20px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="dashboard.php?date=<?php echo $date; ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="chart-header">
            <h1 class="page-title">Food Intake Chart</h1>
            <div class="date-display">Date: <?php echo date('d-m-Y', strtotime($date)); ?></div>
        </div>
        
        <div id="chartContainer">
            <canvas id="foodIntakeChart"></canvas>
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
            const foodIntake = data.map(d => parseFloat(d.Food_Intake) || 0); // Convert to number, use 0 if null

            // Create chart using the chart.js utility
            createLineChart(
                document.getElementById('foodIntakeChart').getContext('2d'),
                'Food Intake (grams)',
                hours,
                foodIntake,
                '#EF476F'
            );
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('chartContainer').innerHTML = '<p class="error-message">Error loading chart data. Please try again later.</p>';
        });
    </script>
</body>
</html>
