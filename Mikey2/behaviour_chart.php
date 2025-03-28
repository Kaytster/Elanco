<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Behaviour Pattern - Doughnut Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
  
</head>
<body>

<h2>Dog Behaviour Pattern </h2>
<div style="width: 300%; max-width: 600px; margin: auto;">
<canvas id="behaviourDoughnutChart" ></canvas>
</div>
<script>
    window.onload = function () {
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const behaviourCounts = {};

        // Count the occurrences of each behaviourdetail
        data.forEach(d => {
            behaviourCounts[d.B_Desc] = (behaviourCounts[d.B_Desc] || 0) + 1;
        });

        if (typeof createDoughnutChart !== "function") {
            console.error("createDoughnutChart is not defined!");
            return;
        }

        // Create the doughnut chart using the behaviourdetail values
        createDoughnutChart(
            document.getElementById('behaviourDoughnutChart').getContext('2d'),
            'Behaviour Pattern',
            Object.keys(behaviourCounts),
            Object.values(behaviourCounts),
            ['red', 'blue', 'green', 'yellow', 'purple']
        );
    })
    .catch(error => console.error("Error fetching data:", error));
};

</script>

</body>
</html>