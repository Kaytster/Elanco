<?php
// Connect to SQLite Database
$pdo = new PDO("sqlite:dog_data1.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the requested hour from the GET request
$hour = isset($_GET['hour']) ? intval($_GET['hour']) : null;

// Base query
$query = "SELECT hour, activity_level, heart_rate, calorie_burn, temperature, water_intake, breathing_rate, barking_frequency, weight, food_intake, behaviour FROM dog_activity WHERE dog_id = 'CANINE001'";

// If an hour is selected, filter the data
if (!is_null($hour)) {
    $query .= " AND hour = :hour";
}

$stmt = $pdo->prepare($query);

// Bind the hour parameter if provided
if (!is_null($hour)) {
    $stmt->bindParam(':hour', $hour, PDO::PARAM_INT);
}

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert to JSON and return
header('Content-Type: application/json');
echo json_encode($data);
?>
