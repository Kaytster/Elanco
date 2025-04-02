<?php
// Connect to SQLite Database
$pdo = new PDO("sqlite:Elanco-Final.db");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Get the requested hour and date from the GET request
$hour = isset($_GET['hour']) ? intval($_GET['hour']) : null;
$date = isset($_GET['date']) ? $_GET['date'] : date('d-m-Y');

// Format the date parameter to match database format if needed
$formattedDate = $date; // Default, already in correct format

// Base query: Join the Activity table with the Behaviour table and the B_Frequency table to get the F_Desc
$query = "
    SELECT a.Activity_ID, a.Dog_ID, a.Behaviour_ID, b.B_Desc, a.Frequency_ID, f.F_Desc, a.Weight, a.D_Date, a.Hour, 
           a.Activity_Level, a.Heart_Rate, a.Calorie_Burnt, a.Temperature, a.Food_Intake, a.Water_Intake, a.Breath_Rate
    FROM Activity a
    INNER JOIN Behaviour b ON a.Behaviour_ID = b.Behaviour_ID
    INNER JOIN B_Frequency f ON a.Frequency_ID = f.Frequency_ID
    WHERE a.Dog_ID = 'CANINE001'
    AND a.D_Date = :date";

// If an hour is selected, filter the data
if (!is_null($hour)) {
    $query .= " AND a.Hour = :hour";
}

// Add order by Hour for proper time series display
$query .= " ORDER BY a.Hour ASC";

$stmt = $pdo->prepare($query);

// Bind the date parameter
$stmt->bindParam(':date', $formattedDate, PDO::PARAM_STR);

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
