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
    
    // Calculate statistical significance for trends
    $significance = [];
    foreach ($metrics as $metric) {
        $significance[$metric] = 'stable'; // Default
        if (abs($trends[$metric]) > 10) {
            $significance[$metric] = $trends[$metric] > 0 ? 'increasing' : 'decreasing';
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
            height: 400px !important;
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
            height: auto !important;
            max-width: none;
            padding: 30px 40px;
            border-radius: 15px;
            background: #f9fbff;
        }
        
        /* Trend card styles */
        .trend-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .trend-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .trend-value {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 10px 0;
        }
        
        .current-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        
        .trend-indicator {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .trend-indicator.positive {
            background-color: rgba(6, 214, 160, 0.2);
            color: #06D6A0;
        }
        
        .trend-indicator.negative {
            background-color: rgba(239, 71, 111, 0.2);
            color: #EF476F;
        }
        
        .trend-indicator.neutral {
            background-color: rgba(255, 209, 102, 0.2);
            color: #FFD166;
        }
        
        .trend-indicator.warning {
            background-color: rgba(255, 159, 28, 0.2);
            color: #FF9F1C;
        }
        
        .trend-period {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        
        /* Insight styles */
        .insight-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .insight-indicator {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .insight-indicator i {
            font-size: 1.5rem;
            color: white;
        }
        
        .insight-indicator.positive {
            background-color: #06D6A0;
        }
        
        .insight-indicator.negative {
            background-color: #EF476F;
        }
        
        .insight-indicator.neutral {
            background-color: #4992FF;
        }
        
        .insight-indicator.warning {
            background-color: #FF9F1C;
        }
        
        .chart-subtitle {
            color: #666;
            margin-top: -15px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        /* Ensure form controls are properly aligned */
        .form {
            display: flex;
            justify-content: flex-start;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .form-group {
            text-align: left;
            min-width: 180px;
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
                height: 300px !important;
            }
            
            .form {
                gap: 10px;
            }
            
            .form-group {
                min-width: 120px;
            }
            
            .chart-container {
                padding: 20px;
            }
            
            .insight-content {
                flex-direction: column;
                text-align: center;
            }
            
            .insight-indicator {
                margin: 0 auto 10px;
            }
        }
        
        @media (max-width: 576px) {
            #comparisonChart {
                height: 250px !important;
            }
            
            .chart-container {
                padding: 15px;
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
                    <option value="CANINE001" <?php echo ($dogID == 'CANINE001') ? 'selected' : ''; ?>>Basil</option>
                    <option value="CANINE002" <?php echo ($dogID == 'CANINE002') ? 'selected' : ''; ?>>Snoopy</option>
                    <option value="CANINE003" <?php echo ($dogID == 'CANINE003') ? 'selected' : ''; ?>>Cooper</option>
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
    
    <!-- Trend Insights Section -->
    <section class="section">
        <h2 class="section-title">Trend Insights</h2>
        <div class="grid">
            <div class="card" style="grid-column: span 2;">
                <div class="card-body">
                    <h3 class="card-title">Activity Analysis</h3>
                    <div class="insight-content">
                        <?php
                        $activityTrend = $trends['Activity_Level'] ?? 0;
                        $activityClass = $activityTrend > 5 ? 'positive' : ($activityTrend < -5 ? 'negative' : 'neutral');
                        $activityIcon = $activityTrend > 5 ? 'fa-running' : ($activityTrend < -5 ? 'fa-bed' : 'fa-walking');
                        $activityText = "";
                        
                        if ($activityTrend > 10) {
                            $activityText = "Your pet's activity level has significantly increased by {$activityTrend}% over this period.";
                        } elseif ($activityTrend > 5) {
                            $activityText = "Your pet has been more active lately with a {$activityTrend}% increase in activity.";
                        } elseif ($activityTrend < -10) {
                            $activityText = "There has been a notable decrease of {$activityTrend}% in your pet's activity level.";
                        } elseif ($activityTrend < -5) {
                            $activityText = "Your pet has been slightly less active with a {$activityTrend}% decrease.";
                        } else {
                            $activityText = "Your pet's activity level has remained relatively stable.";
                        }
                        ?>
                        <div class="insight-indicator <?php echo $activityClass; ?>">
                            <i class="fas <?php echo $activityIcon; ?>"></i>
                        </div>
                        <p><?php echo $activityText; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="card" style="grid-column: span 2;">
                <div class="card-body">
                    <h3 class="card-title">Health Status</h3>
                    <div class="insight-content">
                        <?php
                        $heartTrend = $trends['Heart_Rate'] ?? 0;
                        $tempTrend = $trends['Temperature'] ?? 0;
                        
                        $healthClass = 'positive';
                        $healthIcon = 'fa-heart';
                        $healthText = "Your pet's vital signs are within normal ranges.";
                        
                        if (abs($heartTrend) > 10 || abs($tempTrend) > 5) {
                            $healthClass = 'warning';
                            $healthIcon = 'fa-exclamation-triangle';
                            $healthText = "There are notable changes in your pet's vital signs that may require attention.";
                        }
                        ?>
                        <div class="insight-indicator <?php echo $healthClass; ?>">
                            <i class="fas <?php echo $healthIcon; ?>"></i>
                        </div>
                        <p><?php echo $healthText; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="card" style="grid-column: span 2;">
                <div class="card-body">
                    <h3 class="card-title">Nutritional Balance</h3>
                    <div class="insight-content">
                        <?php
                        $foodTrend = $trends['Food_Intake'] ?? 0;
                        $waterTrend = $trends['Water_Intake'] ?? 0;
                        $weightTrend = $trends['Weight'] ?? 0;
                        
                        $nutritionClass = 'positive';
                        $nutritionIcon = 'fa-utensils';
                        $nutritionText = "Your pet's diet and hydration appear to be well-balanced.";
                        
                        if ($foodTrend > 15 && $weightTrend > 5) {
                            $nutritionClass = 'warning';
                            $nutritionIcon = 'fa-weight';
                            $nutritionText = "Increased food intake combined with weight gain suggests monitoring portion sizes.";
                        } elseif ($foodTrend < -15) {
                            $nutritionClass = 'negative';
                            $nutritionIcon = 'fa-drumstick-bite';
                            $nutritionText = "Your pet's food intake has decreased significantly. Monitor eating habits closely.";
                        } elseif ($waterTrend < -10) {
                            $nutritionClass = 'warning';
                            $nutritionIcon = 'fa-tint';
                            $nutritionText = "Water intake has decreased. Ensure fresh water is always available.";
                        }
                        ?>
                        <div class="insight-indicator <?php echo $nutritionClass; ?>">
                            <i class="fas <?php echo $nutritionIcon; ?>"></i>
                        </div>
                        <p><?php echo $nutritionText; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="card" style="grid-column: span 2;">
                <div class="card-body">
                    <h3 class="card-title">Exercise Recommendations</h3>
                    <div class="insight-content">
                        <?php
                        $activityAvg = $averages['Activity_Level'] ?? 0;
                        $calorieAvg = $averages['Calorie_Burnt'] ?? 0;
                        
                        $exerciseClass = 'positive';
                        $exerciseIcon = 'fa-dog';
                        $exerciseText = "Your pet's exercise level is appropriate for their needs.";
                        
                        if ($activityAvg < 5000) {
                            $exerciseClass = 'warning';
                            $exerciseIcon = 'fa-shoe-prints';
                            $exerciseText = "Consider increasing daily walks or play sessions to boost activity levels.";
                        } elseif ($activityAvg > 12000) {
                            $exerciseClass = 'positive';
                            $exerciseIcon = 'fa-medal';
                            $exerciseText = "Your pet is very active! Make sure they get enough rest between exercise periods.";
                        }
                        ?>
                        <div class="insight-indicator <?php echo $exerciseClass; ?>">
                            <i class="fas <?php echo $exerciseIcon; ?>"></i>
                        </div>
                        <p><?php echo $exerciseText; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    
    <!-- Comparison Chart -->
    <section class="chart-container">
        <h2 class="chart-title">Health Metrics Comparison</h2>
        <div class="form" style="margin-bottom: 20px;">
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
        <div class="chart-wrapper" style="width: 100%; height: 400px; position: relative; margin-top: 30px; border-radius: 10px; background: #FFFFFF; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px;">
            <canvas id="comparisonChart"></canvas>
        </div>
    </section>
    
    <script>
        // Convert PHP data to JavaScript
        const dates = <?php echo json_encode($dates); ?>;
        const metricsData = <?php echo json_encode($metricData); ?>;
        
        // Helper function to format dates nicely
        function formatDate(dateStr) {
            // Convert from "DD-MM-YYYY" to a more readable format
            const parts = dateStr.split('-');
            const day = parts[0];
            const month = parts[1];
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${day} ${monthNames[parseInt(month, 10) - 1]}`;
        }
        
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
            
            // Define colors for the two datasets
            const color1 = 'rgba(0, 103, 177, 0.8)';  // Primary color
            const color2 = 'rgba(239, 71, 111, 0.8)'; // Secondary color
            
            // Determine the maximum value for each dataset to scale appropriately
            const max1 = Math.max(...values1) * 1.1;
            const max2 = Math.max(...values2) * 1.1;
            
            // Set up chart configuration with improved appearance
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: labels[metric1] || metric1.replace('_', ' '),
                            data: values1,
                            borderColor: color1,
                            backgroundColor: 'rgba(0, 103, 177, 0.1)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: color1,
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y',
                            fill: true,
                            tension: 0.2
                        },
                        {
                            label: labels[metric2] || metric2.replace('_', ' '),
                            data: values2,
                            borderColor: color2,
                            backgroundColor: 'rgba(239, 71, 111, 0.1)',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: color2, 
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                            yAxisID: 'y1',
                            fill: true,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    layout: {
                        padding: {
                            top: 10,
                            right: 25,
                            bottom: 10,
                            left: 10
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 10,
                                font: {
                                    size: 11,
                                    weight: 'bold'
                                },
                                padding: 8,
                                color: '#555',
                                callback: function(value, index) {
                                    return formatDate(dates[index]);
                                }
                            }
                        },
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            suggestedMax: max1,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            },
                            title: {
                                display: true,
                                text: labels[metric1] || metric1.replace('_', ' '),
                                color: color1,
                                font: {
                                    weight: 'bold'
                                }
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            suggestedMax: max2,
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                font: {
                                    size: 12
                                }
                            },
                            title: {
                                display: true,
                                text: labels[metric2] || metric2.replace('_', ' '),
                                color: color2,
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                boxWidth: 15,
                                padding: 15,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    return tooltipItems[0].raw.y;
                                },
                                label: function(tooltipItem) {
                                    const dataPoint = tooltipItem.raw;
                                    return [
                                        `Date: ${dataPoint.x}`,
                                        `Activity Level: ${Math.round(dataPoint.v * 10) / 10}`
                                    ];
                                }
                            },
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#333',
                            bodyColor: '#333',
                            borderColor: 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 4
                        }
                    }
                }
            });
            
            // Make chart responsive to window size
            const resizeObserver = new ResizeObserver(entries => {
                for (let entry of entries) {
                    chartInstance.resize();
                }
            });
            
            const chartContainer = document.querySelector('.chart-container');
            if (chartContainer) {
                resizeObserver.observe(chartContainer);
            }
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
