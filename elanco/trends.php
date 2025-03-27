<?php
$db = new SQLite3('Elanco-Final.db');

// Define selectable columns
$columns = [
    "Activity_Level" => "Activity Level",
    "Heart_Rate" => "Heart Rate",
    "Calorie_Burnt" => "Calories Burnt",
    "Temperature" => "Temperature",
    "Food_Intake" => "Food Intake",
    "Water_Intake" => "Water Intake",
    "Breath_Rate" => "Breath Rate"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compare Activity Data</title>
</head>
<body>
    <h2>Select Data to Compare</h2>
    <form method="POST" action="chart.php">
        <label for="column1">Select First Data Set:</label>
        <select name="column1" required>
            <?php foreach ($columns as $col => $label) { ?>
                <option value="<?php echo $col; ?>"><?php echo $label; ?></option>
            <?php } ?>
        </select>

        <label for="column2">Select Second Data Set:</label>
        <select name="column2" required>
            <?php foreach ($columns as $col => $label) { ?>
                <option value="<?php echo $col; ?>"><?php echo $label; ?></option>
            <?php } ?>
        </select>

        <button type="submit">Generate Chart</button>
    </form>
</body>
</html>

