<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Level Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="chart.js"></script>
    <style>
    .card-container {
        display: flex;
        justify-content: center;
        gap: 10px; /* Space between cards */
        flex-wrap: wrap; /* Ensure responsiveness */
    }
    .card {
        flex: 1;
        min-width: 150px;
        max-width: 200px;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        text-align: center;
    }
    .card h5 {
        font-size: 14px;
        margin-bottom: 5px;
    }
    .card h3 {
        font-size: 16px;
        margin: 0;
    }
</style>
</head>
<body>



<h2>Dog Activity Level</h2>
<div class="card-container">
    <div class="card">
        <h5>Date</h5>
        <h3 class="text-primary">01/01/2021</h3>
    </div>
    <div class="card">
        <h5>Pet Name</h5>
        <h3 class="text-success">Snoopy</h3>
    </div>
    <div class="card">
        <h5>ID</h5>
        <h3 class="text-danger">CANINE001</h3>
    </div>
</div>

        <div style="width: 300%; max-width: 900px; margin: auto;">
    <canvas id="activityChart"></canvas>
</div>


<script>
    fetch('fetch_dog_data.php')
    .then(response => response.json())
    .then(data => {
        const hours = data.map(d => d.hour);
        const steps = data.map(d => d.activity_level);
        createBarChart(document.getElementById('activityChart').getContext('2d'), 'Activity Level (Steps)', hours, steps, 'blue');
    });

   
    // Simulating dynamic data from the dashboard
    fetch('fetch_dashboard_data.php')
    .then(response => response.json())
    .then(data => {
        document.getElementById('dateDisplay').innerText = data.date;
        document.getElementById('petNameDisplay').innerText = data.pet_name;
        document.getElementById('petIDDisplay').innerText = data.pet_id;
    })
    .catch(error => console.error("Error fetching data:", error));

</script>

</body>
</html>
