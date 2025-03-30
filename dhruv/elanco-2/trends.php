<?php
// Connect to the database
try {
    $db = new SQLite3('Elanco-Final.db');
    
    // Default date range (last 7 days)
    $endDate = date('d-m-Y');
    $startDate = date('d-m-Y', strtotime('-7 days'));
    
    // Get parameters if provided
    if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
        $startDate = date('d-m-Y', strtotime($_GET['start_date']));
    }
    
    if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
        $endDate = date('d-m-Y', strtotime($_GET['end_date']));
    }
    
    // Default dog ID
    $dogID = 'CANINE001';
    if (isset($_GET['dog_id']) && !empty($_GET['dog_id'])) {
        $dogID = $_GET['dog_id'];
    }
    
    // Get available metrics for the dropdown
    $metricsQuery = "PRAGMA table_info(Activity)";
    $metricsResult = $db->query($metricsQuery);
    
    $metrics = [];
    while ($row = $metricsResult->fetchArray(SQLITE3_ASSOC)) {
        // Only include numeric metrics that make sense for trends
        if (!in_array($row['name'], ['Activity_ID', 'Dog_ID', 'Behaviour_ID', 'Frequency_ID', 'Hour', 'D_Date'])) {
            $metrics[] = $row['name'];
        }
    }
    
    // Default metrics for comparison
    $metric1 = isset($_GET['metric1']) ? $_GET['metric1'] : 'Activity_Level';
    $metric2 = isset($_GET['metric2']) ? $_GET['metric2'] : 'Heart_Rate';
    
    // Get dog data for the selected period
    $query = "
        SELECT a.Activity_ID, a.Dog_ID, a.D_Date, 
               AVG(a.Activity_Level) as Activity_Level, 
               AVG(a.Heart_Rate) as Heart_Rate, 
               AVG(a.Temperature) as Temperature,
               AVG(a.Breath_Rate) as Breath_Rate,
               SUM(a.Food_Intake) as Food_Intake,
               SUM(a.Water_Intake) as Water_Intake,
               SUM(a.Calorie_Burnt) as Calorie_Burnt,
               AVG(a.Weight) as Weight
        FROM Activity a
        WHERE a.Dog_ID = :dogID
        AND a.D_Date BETWEEN :startDate AND :endDate
        GROUP BY a.D_Date
        ORDER BY a.D_Date ASC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->bindValue(':dogID', $dogID, SQLITE3_TEXT);
    $stmt->bindValue(':startDate', $startDate, SQLITE3_TEXT);
    $stmt->bindValue(':endDate', $endDate, SQLITE3_TEXT);
    $result = $stmt->execute();
    
    // Prepare data for charts
    $dates = [];
    $metricData = [];
    
    // Initialize arrays for all metrics
    foreach ($metrics as $metric) {
        $metricData[$metric] = [];
    }
    
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $dates[] = $row['D_Date'];
        
        // Store data for each metric
        foreach ($metrics as $metric) {
            $metricData[$metric][] = $row[$metric];
        }
    }
    
    // Get pet info
    $petInfoQuery = "SELECT * FROM Activity WHERE Dog_ID = :dogID LIMIT 1";
    $petStmt = $db->prepare($petInfoQuery);
    $petStmt->bindValue(':dogID', $dogID, SQLITE3_TEXT);
    $petInfoResult = $petStmt->execute();
    $petInfo = $petInfoResult->fetchArray(SQLITE3_ASSOC);
    
    // Get pet name from landingpage.php data
    $petName = "Pet";
    if ($dogID == "CANINE001") {
        $petName = "Snoopy";
    } elseif ($dogID == "CANINE002") {
        $petName = "Charlie";
    } elseif ($dogID == "CANINE003") {
        $petName = "Teddy";
    }
    
    // Calculate average values for the period
    $averages = [];
    foreach ($metrics as $metric) {
        $avgQuery = "
            SELECT AVG({$metric}) as avg_value 
            FROM Activity 
            WHERE Dog_ID = :dogID 
            AND D_Date BETWEEN :startDate AND :endDate";
        
        $avgStmt = $db->prepare($avgQuery);
        $avgStmt->bindValue(':dogID', $dogID, SQLITE3_TEXT);
        $avgStmt->bindValue(':startDate', $startDate, SQLITE3_TEXT);
        $avgStmt->bindValue(':endDate', $endDate, SQLITE3_TEXT);
        $avgResult = $avgStmt->execute();
        $avgRow = $avgResult->fetchArray(SQLITE3_ASSOC);
        
        $averages[$metric] = round($avgRow['avg_value'], 2);
    }
    
    // Calculate trends (percentage change from first to last day)
    $trends = [];
    foreach ($metrics as $metric) {
        if (count($metricData[$metric]) > 1) {
            $firstValue = $metricData[$metric][0];
            $lastValue = $metricData[$metric][count($metricData[$metric]) - 1];
            
            if ($firstValue > 0) { // Avoid division by zero
                $percentChange = (($lastValue - $firstValue) / $firstValue) * 100;
                $trends[$metric] = round($percentChange, 1);
            } else {
                $trends[$metric] = 0;
            }
        } else {
            $trends[$metric] = 0;
        }
    }
    
} catch (Exception $e) {
    echo "Database error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Health Trends</title>
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include 'navbar.php'; ?>
    <style>
        .filter-section {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: flex-end;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border-radius: var(--radius);
            border: 1px solid #ddd;
            background: white;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 103, 177, 0.1);
            outline: none;
        }
        
        .stats-overview {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 200px;
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            width: 100%;
            background: var(--gradient);
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
            margin-bottom: 5px;
        }
        
        .stat-trend {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
        }
        
        .trend-up {
            color: #28a745;
        }
        
        .trend-down {
            color: #dc3545;
        }
        
        .trend-neutral {
            color: #6c757d;
        }
        
        .chart-container {
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }
        
        .chart-container canvas {
            height: 300px !important;
            max-width: 100%;
        }
        
        .chart-title {
            font-size: 1.3rem;
            margin-bottom: 15px;
        }
        
        .comparison-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .comparison-controls .form-group {
            flex: 1;
            min-width: 150px;
        }
        
        @media (max-width: 992px) {
            .filter-form {
                flex-direction: column;
                gap: 10px;
            }
            
            .form-group {
                width: 100%;
            }
            
            .stat-card {
                min-width: 150px;
            }
        }
        
        @media (max-width: 768px) {
            .chart-container {
                padding: 15px;
            }
            
            .chart-container canvas {
                height: 250px !important;
            }
            
            .chart-title {
                font-size: 1.2rem;
                margin-bottom: 10px;
            }
            
            .filter-section {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="page-title">Pet Health Trends</h1>
        <p class="section-subtitle">Track and analyze your pet's health metrics over time</p>
        
        <!-- Filter Section -->
        <section class="filter-section">
            <form method="get" class="filter-form">
                <div class="form-group">
                    <label for="dog_id">Select Pet</label>
                    <select name="dog_id" id="dog_id" class="form-control">
                        <option value="CANINE001" <?php echo ($dogID == 'CANINE001') ? 'selected' : ''; ?>>Snoopy</option>
                        <option value="CANINE002" <?php echo ($dogID == 'CANINE002') ? 'selected' : ''; ?>>Charlie</option>
                        <option value="CANINE003" <?php echo ($dogID == 'CANINE003') ? 'selected' : ''; ?>>Teddy</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" 
                           value="<?php echo date('Y-m-d', strtotime($startDate)); ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" 
                           value="<?php echo date('Y-m-d', strtotime($endDate)); ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="ui-button">
                        <span><i class="fas fa-filter"></i> Apply Filters</span>
                    </button>
                </div>
            </form>
        </section>
        
        <!-- Stats Overview -->
        <h2 class="section-title">Health Overview: <?php echo $petName; ?></h2>
        <section class="stats-overview">
            <div class="stat-card">
                <h3 class="stat-title">Avg. Activity Level</h3>
                <div class="stat-value"><?php echo $averages['Activity_Level']; ?></div>
                <div class="stat-trend <?php echo $trends['Activity_Level'] > 0 ? 'trend-up' : ($trends['Activity_Level'] < 0 ? 'trend-down' : 'trend-neutral'); ?>">
                    <?php if ($trends['Activity_Level'] > 0): ?>
                        <i class="fas fa-arrow-up"></i> <?php echo abs($trends['Activity_Level']); ?>%
                    <?php elseif ($trends['Activity_Level'] < 0): ?>
                        <i class="fas fa-arrow-down"></i> <?php echo abs($trends['Activity_Level']); ?>%
                    <?php else: ?>
                        <i class="fas fa-minus"></i> 0%
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Avg. Heart Rate</h3>
                <div class="stat-value"><?php echo $averages['Heart_Rate']; ?> bpm</div>
                <div class="stat-trend <?php echo $trends['Heart_Rate'] > 0 ? 'trend-up' : ($trends['Heart_Rate'] < 0 ? 'trend-down' : 'trend-neutral'); ?>">
                    <?php if ($trends['Heart_Rate'] > 0): ?>
                        <i class="fas fa-arrow-up"></i> <?php echo abs($trends['Heart_Rate']); ?>%
                    <?php elseif ($trends['Heart_Rate'] < 0): ?>
                        <i class="fas fa-arrow-down"></i> <?php echo abs($trends['Heart_Rate']); ?>%
                    <?php else: ?>
                        <i class="fas fa-minus"></i> 0%
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Avg. Weight</h3>
                <div class="stat-value"><?php echo $averages['Weight']; ?> kg</div>
                <div class="stat-trend <?php echo $trends['Weight'] > 0 ? 'trend-up' : ($trends['Weight'] < 0 ? 'trend-down' : 'trend-neutral'); ?>">
                    <?php if ($trends['Weight'] > 0): ?>
                        <i class="fas fa-arrow-up"></i> <?php echo abs($trends['Weight']); ?>%
                    <?php elseif ($trends['Weight'] < 0): ?>
                        <i class="fas fa-arrow-down"></i> <?php echo abs($trends['Weight']); ?>%
                    <?php else: ?>
                        <i class="fas fa-minus"></i> 0%
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Avg. Temperature</h3>
                <div class="stat-value"><?php echo $averages['Temperature']; ?> °C</div>
                <div class="stat-trend <?php echo $trends['Temperature'] > 0 ? 'trend-up' : ($trends['Temperature'] < 0 ? 'trend-down' : 'trend-neutral'); ?>">
                    <?php if ($trends['Temperature'] > 0): ?>
                        <i class="fas fa-arrow-up"></i> <?php echo abs($trends['Temperature']); ?>%
                    <?php elseif ($trends['Temperature'] < 0): ?>
                        <i class="fas fa-arrow-down"></i> <?php echo abs($trends['Temperature']); ?>%
                    <?php else: ?>
                        <i class="fas fa-minus"></i> 0%
                    <?php endif; ?>
                </div>
            </div>
        </section>
        
        <!-- Activity Trend Chart -->
        <section class="chart-container">
            <h2 class="chart-title">Activity Level Trend</h2>
            <canvas id="activityChart" height="300"></canvas>
        </section>
        
        <!-- Comparison Chart -->
        <section class="chart-container">
            <h2 class="chart-title">Health Metrics Comparison</h2>
            <div class="comparison-controls">
                <div class="form-group">
                    <label for="metric1">First Metric</label>
                    <select id="metric1" class="form-control">
                        <?php foreach ($metrics as $metric): ?>
                            <option value="<?php echo $metric; ?>" <?php echo ($metric == $metric1) ? 'selected' : ''; ?>>
                                <?php echo str_replace('_', ' ', $metric); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="metric2">Second Metric</label>
                    <select id="metric2" class="form-control">
                        <?php foreach ($metrics as $metric): ?>
                            <option value="<?php echo $metric; ?>" <?php echo ($metric == $metric2) ? 'selected' : ''; ?>>
                                <?php echo str_replace('_', ' ', $metric); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <button id="updateComparisonChart" class="ui-button">
                        <span><i class="fas fa-sync-alt"></i> Update Chart</span>
                    </button>
                </div>
            </div>
            <canvas id="comparisonChart" height="300"></canvas>
        </section>
    </div>
    
    <script>
        // Convert PHP data to JavaScript
        const dates = <?php echo json_encode($dates); ?>;
        const metricsData = <?php echo json_encode($metricData); ?>;
        
        // Create activity level chart
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Activity Level',
                    data: metricsData.Activity_Level,
                    borderColor: '#0067B1',
                    backgroundColor: 'rgba(0, 103, 177, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#0067B1',
                    pointBorderColor: '#fff',
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12
                            },
                            padding: 10
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date',
                            padding: 10,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Activity Level',
                            padding: 10,
                            font: {
                                size: 12,
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });
        
        // Create comparison chart
        let chartInstance;
        
        function createComparisonChart(metric1, metric2) {
            // Define pretty labels for metrics
            const labels = {
                'Activity_Level': 'Activity Level',
                'Heart_Rate': 'Heart Rate (bpm)',
                'Temperature': 'Temperature (°C)',
                'Breath_Rate': 'Breath Rate',
                'Food_Intake': 'Food Intake (g)',
                'Water_Intake': 'Water Intake (ml)',
                'Calorie_Burnt': 'Calories Burnt',
                'Weight': 'Weight (kg)'
            };
            
            // Destroy previous chart instance if it exists
            if (chartInstance) {
                chartInstance.destroy();
            }
            
            // Get data for selected metrics
            const values1 = metricsData[metric1];
            const values2 = metricsData[metric2];
            
            // Create new chart
            let ctx = document.getElementById("comparisonChart").getContext("2d");
            chartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: labels[metric1],
                            data: values1,
                            borderColor: "#0067B1",
                            backgroundColor: "rgba(0, 103, 177, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: "#0067B1",
                            pointBorderColor: "#fff",
                            pointRadius: 5,
                            pointHoverRadius: 7
                        },
                        {
                            label: labels[metric2],
                            data: values2,
                            borderColor: "#EF476F",
                            backgroundColor: "rgba(239, 71, 111, 0.1)",
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: "#EF476F",
                            pointBorderColor: "#fff",
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    size: 12
                                },
                                padding: 10
                            }
                        }
                    },
                    scales: {
                        x: { 
                            title: { 
                                display: true, 
                                text: "Date",
                                padding: 10,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        },
                        y: { 
                            title: { 
                                display: true, 
                                text: "Values",
                                padding: 10,
                                font: {
                                    size: 12,
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Initialize comparison chart
        createComparisonChart('<?php echo $metric1; ?>', '<?php echo $metric2; ?>');
        
        // Update comparison chart when button is clicked
        document.getElementById('updateComparisonChart').addEventListener('click', function() {
            const metric1 = document.getElementById('metric1').value;
            const metric2 = document.getElementById('metric2').value;
            createComparisonChart(metric1, metric2);
        });
    </script>
</body>
</html>
