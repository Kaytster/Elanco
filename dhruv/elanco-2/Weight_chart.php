<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weight Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="new.css">
    <?php include 'navbar.php';?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 30px;
            margin: 30px auto;
            max-width: 900px;
            position: relative;
            overflow: hidden;
        }
        
        .chart-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
        }
        
        .chart-title {
            color: var(--primary);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
            display: inline-block;
        }
        
        .chart-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--gradient);
            border-radius: 10px;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .back-button {
            color: var(--primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            transition: var(--transition);
        }
        
        .back-button:hover {
            transform: translateX(-5px);
        }
        
        .chart-info {
            background-color: rgba(0, 103, 177, 0.05);
            border-radius: var(--radius);
            padding: 15px;
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        
        .info-item {
            text-align: center;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: var(--primary-light);
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
        }
        
        canvas {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="chart-container">
        <div class="chart-header">
            <h2 class="chart-title">Weight Over Time</h2>
            <a href="dashboard.php?date=<?php echo isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); ?>" class="back-button">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
        
        <canvas id="weightChart"></canvas>
        
        <div class="chart-info">
            <div class="info-item">
                <div class="info-label">Average Weight</div>
                <div class="info-value" id="avgWeight">-</div>
            </div>
            <div class="info-item">
                <div class="info-label">Min Weight</div>
                <div class="info-value" id="minWeight">-</div>
            </div>
            <div class="info-item">
                <div class="info-label">Max Weight</div>
                <div class="info-value" id="maxWeight">-</div>
            </div>
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

    // Get date from URL parameter or use current date
    const urlParams = new URLSearchParams(window.location.search);
    const selectedDate = urlParams.get('date') || new Date().toISOString().split('T')[0];
    
    // Add date parameter to the fetch request
    fetch(`fetch_dog_data.php?date=${selectedDate}`)
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.Hour);
        const weight = data.map(d => d.Weight);
        
        createLineChart(
            document.getElementById('weightChart').getContext('2d'),
            'Weight (kg)',
            hours,
            weight,
            '#0067B1'
        );
        
        // Calculate statistics
        if (weight.length > 0) {
            // Average weight
            const avgWeight = weight.reduce((sum, val) => sum + parseFloat(val), 0) / weight.length;
            document.getElementById('avgWeight').textContent = avgWeight.toFixed(1) + ' kg';
            
            // Min weight
            const minWeight = Math.min(...weight);
            document.getElementById('minWeight').textContent = minWeight + ' kg';
            
            // Max weight
            const maxWeight = Math.max(...weight);
            document.getElementById('maxWeight').textContent = maxWeight + ' kg';
        }
    })
    .catch(error => console.error('Error fetching data:', error));
</script>

</body>
</html>
