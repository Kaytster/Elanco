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
    <title>Temperature Chart</title>
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="chart.js"></script>
    <script src="notification.js"></script>
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
        <a href="dashboard.php?date=<?php echo $date; ?>&pet_id=<?php echo $petId; ?>&dog_id=<?php echo $dogId; ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="chart-header">
            <h1 class="page-title">Temperature Chart</h1>
            <div class="date-display">Date: <?php echo date('d-m-Y', strtotime($date)); ?></div>
        </div>
        
        <div id="chartContainer">
            <canvas id="temperatureChart"></canvas>
        </div>
        
        <div class="summary-stats">
            <div class="stat-card">
                <h3 class="stat-title">Average</h3>
                <div class="stat-value" id="avgTemperature">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Maximum</h3>
                <div class="stat-value" id="maxTemperature">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Minimum</h3>
                <div class="stat-value" id="minTemperature">-</div>
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
            const temperatures = data.map(d => parseFloat(d.Temperature) || 0);
            
            // Calculate statistics
            const avgTemperature = (temperatures.reduce((a, b) => a + b, 0) / temperatures.length).toFixed(1);
            const maxTemperature = Math.max(...temperatures).toFixed(1);
            const minTemperature = Math.min(...temperatures).toFixed(1);
            
            // Update statistics display
            document.getElementById('avgTemperature').textContent = avgTemperature + ' °C';
            document.getElementById('maxTemperature').textContent = maxTemperature + ' °C';
            document.getElementById('minTemperature').textContent = minTemperature + ' °C';

            // Create chart using the chart.js utility
            createLineChart(
                document.getElementById('temperatureChart').getContext('2d'),
                'Temperature (°C)',
                hours,
                temperatures,
                '#FFD166'
            );
            
            // Check for temperature alerts
            checkTemperature(temperatures, hours);
        })
        .catch(error => {
            console.error('Error fetching data:', error);
            document.getElementById('chartContainer').innerHTML = '<p class="error-message">Error loading chart data. Please try again later.</p>';
        });
        
        function createLineChart(context, label, labels, data, borderColor) {
            new Chart(context, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: 'rgba(0, 0, 0, 0)', // Transparent background for line
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (Hours)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: label
                            }
                        }
                    }
                }
            });
        }

        function checkTemperature(temps, hours) {
            const highThreshold = 39.5; // High temperature threshold
            const lowThreshold = 37.0;  // Low temperature threshold

            temps.forEach((temp, index) => {
                if (temp > highThreshold || temp < lowThreshold) {
                    if (typeof showNotification === 'function') {
                        showNotification(
                            'Temperature Alert!',
                            `Temperature detected at ${hours[index]}:00 - ${temp} °C`,
                            'warning'
                        );
                    } else {
                        Swal.fire({
                            title: 'Temperature Alert!',
                            text: `Temperature detected at ${hours[index]}:00 - ${temp} °C`,
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    </script>

    <!-- Notification container -->
    <div id="notification-container"></div>
</body>
</html>
