<?php
$servername = "localhost";
$username = "root";  // dont know how to use xampp so might have to change this to fit 
$password = "";      
$dbname = "elanco";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$pet_name = "Snoopy";  // probs need to chnage for the chosen dog
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
        body { background-color: #E8DEF8; }
        .header { background-color: #2C6AC2; color: white; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; }
        .date-picker { margin-bottom: 20px; position: absolute; top: 10px; right: 10px; z-index: 1000; }
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
        document.getElementById('date').addEventListener('change', (event) => {
            const selectedDate = event.target.value;
            window.location.href = `yourpage.php?date=${selectedDate}`; // Changes to whatever page is after this 
        });
    </script>
</body>
</html>
