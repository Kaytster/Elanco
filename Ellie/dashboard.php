<?php
$servername = "localhost";
$username = "root";  //dont know how to use xampp as doesnt work for me so change this to fit xampp
$password = "";      
$dbname = "elanco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pet_name = "Snoopy";  // probs need to change to fit the chosen dogs 
$date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");  

$sql = "SELECT weight, behaviour, activity, heart_rate, temp, food, water, breathing_rate, barking_frequency FROM pet_stats 
        WHERE pet_name = ? AND date = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $pet_name, $date);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Stats</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #E6D9F4; }
        .header { background-color: #2C6AC2; color: white; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; }
        .date-picker { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <span class="badge bg-primary">Snoopy</span>
        <span class="badge bg-primary">CANINE001</span>
    </div>
    <div class="container mt-4">
        <h2 class="text-primary">Recent Stats</h2>
        <label for="date">Date:</label>
        <input type="date" id="date" class="form-control date-picker" value="<?= date('Y-m-d') ?>">
        <canvas id="statsChart" width="400" height="400"></canvas>
    </div>
    <script>
        let chartInstance;
        async function fetchStats(date) {
            const response = await fetch(`stats.php?date=${date}`);
            const data = await response.json();
            return data;
        }
        async function updateChart(date) {
            const data = await fetchStats(date);
            const ctx = document.getElementById('statsChart').getContext('2d');
            if (chartInstance) {
                chartInstance.destroy();
            }
            chartInstance = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Weight', 'Behaviour', 'Activity', 'Heart Rate', 'Temp', 'Food', 'Water', 'Breathing Rate', 'Barking Frequency'],
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#66D3FA', '#B8A9C9', '#D4A5A5']
                    }]
                }
            });
        }
        document.getElementById('date').addEventListener('change', (event) => {
            updateChart(event.target.value);
        });
        updateChart(document.getElementById('date').value);
    </script>
</body>
</html>
