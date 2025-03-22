<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barking Frequency Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
</head>
<body>

<h2>Dog Barking Frequency</h2>
<div style="width: 300%; max-width: 900px; margin: auto;">
<canvas id="barkingFrequencyChart"></canvas>
</div>
<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        
        // Convert Barking Frequency to numerical values
        const barkingValues = data.map(d => {
            switch (d.barking_frequency.toLowerCase()) {
                case 'none': return 0;
                case 'low': return 1;
                case 'medium': return 2;
                case 'high': return 3;
                default: return 0;
            }
        });

        createLineChart(
            document.getElementById('barkingFrequencyChart').getContext('2d'),
            'Barking Frequency (0=None, 1=Low, 2=Medium, 3=High)',
            hours,
            barkingValues,
            'brown'
        );
    });
</script>

</body>
</html>
