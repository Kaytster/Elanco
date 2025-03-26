<?php
$host = 'localhost'; 
$dbname = 'elanco'; //i dont think this is right
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
//these will need changing the match what the names are in the database sorry i couldnt access the ERD 
if (isset($_GET['type'])) { 
    $type = $_GET['type'];

    if ($type == "activity_calories") {
        $stmt = $pdo->query("
            SELECT time AS label, calories AS value 
            FROM activity 
            JOIN calories_burnt  ON id = activity_id
            ORDER BY time
        ");
        $label = "Calories Burnt vs. Activity Level";

    } elseif ($type == "activity_heart") {
        $stmt = $pdo->query("
            SELECT date AS label, heart_rate AS value
            FROM activity
            JOIN heart_rate  ON id = activity_id
            ORDER BY date
        ");
        $label = "Heart Rate vs. Activity Level";

    } elseif ($type == "behavior_barking") {
        $stmt = $pdo->query("
            SELECT behavior_pattern AS label, barkingfrequency AS value
            FROM barking
        ");
        $label = "Barking Frequency vs. Behavior Pattern";
    } else {
        echo json_encode(["error" => "Invalid data type"]);
        exit;
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $labels = array_column($data, 'label');
    $values = array_column($data, 'value');

    echo json_encode(["labels" => $labels, "values" => $values, "label" => $label]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trends | Pet Activity</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E7D6F8;
            text-align: center;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        .container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 60%;
            margin: auto;
        }
        select {
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }
        canvas {
            max-width: 100%;
        }
    </style>
</head>
<body>

    <h2>Pet Activity Trends</h2>
    <div class="container">
        <select id="dataSelector">
            <option value="activity_calories">Activity Level vs. Calories Burnt</option>
            <option value="activity_heart">Activity Level vs. Heart Rate</option>
            <option value="behavior_barking">Behavior Pattern vs. Barking Frequency</option>
        </select>

        <canvas id="myChart"></canvas>
    </div>

    <script>
        let chart;

        document.getElementById("dataSelector").addEventListener("change", function() {
            fetchGraphData(this.value);
        });

        function fetchGraphData(option) {
            fetch('trends.php?type=' + option)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        updateChart(data.labels, data.values, data.label);
                    }
                });
        }

        function updateChart(labels, values, label) {
            if (chart) {
                chart.destroy();
            }

            const ctx = document.getElementById("myChart").getContext("2d");
            chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: values,
                        borderColor: ["#0095FF", "#FF3BA7", "#FF9900"],
                        borderWidth: 3,
                        pointRadius: 5,
                        pointBackgroundColor: "#FFFFFF",
                        pointBorderColor: ["#0095FF", "#FF3BA7", "#FF9900"],
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: "right",
                            labels: {
                                usePointStyle: true,
                                font: { size: 14 }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        fetchGraphData("activity_calories");
    </script>

</body>
</html>

