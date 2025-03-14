<?php
// Connect to SQLite Database
$pdo = new PDO("sqlite:dog_data1.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Query to get the data
$query = "SELECT hour, activity_level, heart_rate, calorie_burn FROM dog_activity WHERE dog_id = 'CANINE001' ORDER BY hour";
$stmt = $pdo->query($query);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert to JSON and return
header('Content-Type: application/json');
echo json_encode($data);
?>
