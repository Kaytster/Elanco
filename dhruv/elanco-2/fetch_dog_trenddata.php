<?php
// Connect to SQLite Database
try {
    $pdo = new PDO("sqlite:Elanco-Final.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["error" => "Database connection failed: " . $e->getMessage()])); 
}

// Get parameters from GET request
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
$dogIDs = isset($_GET['dog_ids']) ? $_GET['dog_ids'] : null;  // Now accepting multiple dog IDs
$metric1 = isset($_GET['metric1']) ? $_GET['metric1'] : 'Activity_Level'; // Default metric1
$metric2 = isset($_GET['metric2']) ? $_GET['metric2'] : 'Heart_Rate'; // Default metric2

// Validate input
if (!$startDate || !$endDate || !$dogIDs) {
    die(json_encode(["error" => "Missing parameters"]));
}

// Ensure the dog IDs are formatted correctly
$dogIDsArray = explode(',', $dogIDs); // Split comma-separated dog IDs into an array

// Ensure correct date format
$startDate = date('d-m-Y', strtotime($startDate));
$endDate = date('d-m-Y', strtotime($endDate));

// Prepare SQL query for multiple dog IDs
$query = "
    SELECT a.Activity_ID, a.Dog_ID, a.Behaviour_ID, b.B_Desc AS Behaviour, 
           a.Frequency_ID, f.F_Desc AS Frequency, a.Weight, a.D_Date, a.Hour, 
           a.Activity_Level, a.Heart_Rate, a.Calorie_Burnt, a.Temperature, 
           a.Food_Intake, a.Water_Intake, a.Breath_Rate
    FROM Activity a
    INNER JOIN Behaviour b ON a.Behaviour_ID = b.Behaviour_ID
    INNER JOIN B_Frequency f ON a.Frequency_ID = f.Frequency_ID
    WHERE a.Dog_ID IN (" . implode(",", array_map(function($id) { return "'$id'"; }, $dogIDsArray)) . ")
    AND a.D_Date BETWEEN :startDate AND :endDate
    ORDER BY a.D_Date ASC";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':startDate', $startDate, PDO::PARAM_STR);
$stmt->bindParam(':endDate', $endDate, PDO::PARAM_STR);

$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debugging - If no data is found, log and return empty JSON
if (!$data) {
    error_log("No data found for Dog_IDs: $dogIDs from $startDate to $endDate");
    echo json_encode([]);
    exit;
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
