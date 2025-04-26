<?php
// Get date parameter or set default
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="new.css">
    <link rel="stylesheet" href="chart_style.css">
    <?php include 'navbar.php';?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>
    <div class="container">
        <a href="dashboard.php?date=<?php echo $date; ?>" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
        
        <div class="chart-header">
            <h1 class="page-title">Weight Chart</h1>
            <div class="date-display">Date: <?php echo date('d-m-Y', strtotime($date)); ?></div>
        </div>
        
        <div id="chartContainer">
            <canvas id="weightChart"></canvas>
        </div>
        
        <div class="summary-stats">
            <div class="stat-card">
                <h3 class="stat-title">Average Weight</h3>
                <div class="stat-value" id="avgWeight">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Minimum</h3>
                <div class="stat-value" id="minWeight">-</div>
            </div>
            
            <div class="stat-card">
                <h3 class="stat-title">Maximum</h3>
                <div class="stat-value" id="maxWeight">-</div>
            </div>
        </div>
    </div>

    <script>
        function createLineChart(context, label, labels, data, borderColor) {
            return new Chart(context, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: 'rgba(0, 103, 177, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: borderColor,
                        pointBorderColor: '#fff',
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Time (Hours)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#0067B1'
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#333'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Weight (kg)',
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                color: '#0067B1'
                            },
                            grid: {
                                color: 'rgba(0, 103, 177, 0.1)'
                            },
                            ticks: {
                                color: '#333'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 103, 177, 0.8)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 14
                            },
                            padding: 12,
                            cornerRadius: 8
                        }
                    }
                }
            });
        }

        // Format date for API call
        const queryDate = '<?php echo date('d-m-Y', strtotime($date)); ?>';
        
        // Add date parameter to the fetch request
        fetch('fetch_dog_data.php?date=' + queryDate)
        .then(response => response.json())
        .then(data => {
            const hours = data.map(d => d.Hour);
            const weight = data.map(d => parseFloat(d.Weight) || 0);
            
            // Calculate statistics
            const avgWeight = (weight.reduce((sum, val) => sum + parseFloat(val), 0) / weight.length).toFixed(1);
            const minWeight = Math.min(...weight).toFixed(1);
            const maxWeight = Math.max(...weight).toFixed(1);
            
            // Update statistics display
            document.getElementById('avgWeight').textContent = avgWeight + ' kg';
            document.getElementById('minWeight').textContent = minWeight + ' kg';
            document.getElementById('maxWeight').textContent = maxWeight + ' kg';

            // Create chart
            createLineChart(
                document.getElementById('weightChart').getContext('2d'),
                'Weight (kg)',
                hours,
                weight,
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
