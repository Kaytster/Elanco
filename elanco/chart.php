<?php
$db = new SQLite3('Elanco-Final.db');

$column1 = $_POST['column1'];
$column2 = $_POST['column2'];

$query = "SELECT D_Date, $column1, $column2 FROM Activity ORDER BY D_Date";
$result = $db->query($query);

$dates = [];
$data1 = [];
$data2 = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $dates[] = $row['D_Date'];
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
            }
        });
    </script>
</body>
</html>

