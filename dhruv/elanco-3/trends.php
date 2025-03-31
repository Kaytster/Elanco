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
        /* Chart-specific styles only - layout is now handled by layout.css */
        #comparisonChart {
            width: 100% !important;
            height: 300px !important;
            max-width: 100%;
            display: block;
            position: relative;
            margin: 0;
        }
        
        /* Override any existing styles that might center content */
        .chart-container {
            text-align: left;
            margin-left: 0;
            margin-right: 0;
            width: 100%;
        }
        
        /* Ensure form controls are properly aligned */
        .form {
            justify-content: flex-start;
            align-items: flex-end;
        }
        
        .form-group {
            text-align: left;
        }
        
        /* Small adjustments for chart scales */
        .chart-tooltip {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 5px 8px;
            font-size: 12px;
        }
        
        @media (max-width: 768px) {
            #comparisonChart {
                height: 250px !important;
            }
        }
        
        @media (max-width: 576px) {
            #comparisonChart {
                height: 220px !important;
            }
        }
    </style>
</head>
<body>
    <!-- No container div needed here as it's already opened in navbar.php -->
    <h1 class="page-title">Pet Health Trends</h1>
    <p class="section-subtitle">Track and analyze your pet's health metrics over time</p>
    
    <!-- Filter Section -->
    <section class="section">
        <form method="get" class="form">
            <div class="form-group">
                <label for="dog_id" class="form-label">Select Pet</label>
                <select name="dog_id" id="dog_id" class="form-control">
                    <option value="CANINE001" <?php echo ($dogID == 'CANINE001') ? 'selected' : ''; ?>>Snoopy</option>
                    <option value="CANINE002" <?php echo ($dogID == 'CANINE002') ? 'selected' : ''; ?>>Charlie</option>
                    <option value="CANINE003" <?php echo ($dogID == 'CANINE003') ? 'selected' : ''; ?>>Teddy</option>
                </select>
            </div>
            <div class="form-group">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" 
                       value="<?php echo date('Y-m-d', strtotime($startDate)); ?>">
            </div>
            <div class="form-group">
                <label for="end_date" class="form-label">End Date</label>
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
    
    
    <!-- Comparison Chart -->
    <section class="chart-container">
        <h2 class="chart-title">Health Metrics Comparison</h2>
        <div class="form" style="margin-bottom: 15px;">
            <div class="form-group">
                <label for="metric1" class="form-label">First Metric</label>
                <select id="metric1" class="form-control">
                    <?php foreach ($metrics as $metric): ?>
                        <option value="<?php echo $metric; ?>" <?php echo ($metric == $metric1) ? 'selected' : ''; ?>>
                            <?php echo str_replace('_', ' ', $metric); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="metric2" class="form-label">Second Metric</label>
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
        <div style="width: 100%; height: 300px;">
            <canvas id="comparisonChart" height="300"></canvas>
        </div>
    </section>
    
    <script>
        // Convert PHP data to JavaScript
        const dates = <?php echo json_encode($dates); ?>;
        const metricsData = <?php echo json_encode($metricData); ?>;
        
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
                            pointRadius: 4,
                            pointHoverRadius: 6
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
                            pointRadius: 4,
                            pointHoverRadius: 6
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
                            align: 'start',
                            labels: {
                                font: {
                                    size: 11
                                },
                                padding: 8,
                                boxWidth: 15
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            position: 'nearest'
                        }
                    },
                    layout: {
                        padding: {
                            left: 0,
                            right: 0,
                            top: 0,
                            bottom: 0
                        }
                    },
                    scales: {
                        x: { 
                            title: { 
                                display: true, 
                                text: "Date",
                                padding: 8,
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                },
                                align: 'start'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: 10
                                },
                                align: 'start'
                            },
                            grid: {
                                display: false
                            }
                        },
                        y: { 
                            title: { 
                                display: true, 
                                text: "Values",
                                padding: 8,
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                },
                                align: 'start'
                            },
                            ticks: {
                                font: {
                                    size: 10
                                },
                                align: 'start'
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }
        
        // Initialize chart after DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Small timeout to ensure DOM is ready
            setTimeout(function() {
                createComparisonChart('<?php echo $metric1; ?>', '<?php echo $metric2; ?>');
            }, 200);
        });
        
        // Update comparison chart when button is clicked
        document.getElementById('updateComparisonChart').addEventListener('click', function() {
            const metric1 = document.getElementById('metric1').value;
            const metric2 = document.getElementById('metric2').value;
            createComparisonChart(metric1, metric2);
        });
        
        // Redraw chart on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const metric1 = document.getElementById('metric1').value;
                const metric2 = document.getElementById('metric2').value;
                createComparisonChart(metric1, metric2);
            }, 250);
        });
    </script>
    </div> <!-- Close container div properly -->
</body>
</html>
