<?php
$db = new SQLite3('Elanco-Final.db');

if (!$db) {
    die("Database connection failed.");
}

// Get user inputs
$column1 = $_POST['column1'];
$column2 = $_POST['column2'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Fetch data within the selected date range
$query = "SELECT D_Date, Hour, \"$column1\", \"$column2\" 
          FROM Activity 
          WHERE DATE(SUBSTR(D_Date, 7, 4) || '-' || SUBSTR(D_Date, 4, 2) || '-' || SUBSTR(D_Date, 1, 2)) 
                BETWEEN DATE(:start_date) AND DATE(:end_date)
          ORDER BY DATE(SUBSTR(D_Date, 7, 4) || '-' || SUBSTR(D_Date, 4, 2) || '-' || SUBSTR(D_Date, 1, 2))";

$stmt = $db->prepare($query);
$stmt->bindValue(':start_date', $start_date, SQLITE3_TEXT);
$stmt->bindValue(':end_date', $end_date, SQLITE3_TEXT);
$result = $stmt->execute();

if (!$result) {
    die("Error executing query: " . $db->lastErrorMsg());
}

$dates = [];
$hours = [];
$data1 = [];
$data2 = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $dates[] = $row['D_Date'];
    $hours[] = $row['Hour'];  // Get the hour for each data point
    $data1[] = $row[$column1];
    $data2[] = $row[$column2];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Data Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="activityChart"></canvas>

    <script>
        const labels = <?php echo json_encode($dates); ?>;
        const hours = <?php echo json_encode($hours); ?>;  // Store the hours
        const data1 = <?php echo json_encode($data1); ?>;
        const data2 = <?php echo json_encode($data2); ?>;

        const ctx = document.getElementById('activityChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: '<?php echo ucfirst($column1); ?>',
                        data: data1,
                        borderColor: 'blue',
                        fill: false
                    },
                    {
                        label: '<?php echo ucfirst($column2); ?>',
                        data: data2,
                        borderColor: 'red',
                        fill: false
                    }
                ]
            },
            options: {
                plugins: {
                    tooltip: {
                        callbacks: {
                            // Display the hour in the tooltip
                            title: function(tooltipItem) {
                                let index = tooltipItem[0].dataIndex;
                                return labels[index] + ' (Hour: ' + hours[index] + ')';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
