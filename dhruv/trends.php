<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dog Trends Comparison</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f8f9fa; text-align: center; padding: 20px; }
        .container { max-width: 80%; margin: auto; padding: 20px; }
        select, input, button { margin: 10px; padding: 8px; font-size: 1rem; }
        .chart-container { position: relative; height: 400px; width: 100%; }
    </style>
</head>
<body>

    <h2>Compare Dog Activity Metrics</h2>

    <div class="container">
        <!-- Multi-Select for Dog IDs -->
        <label for="dogIDs">Select Dog(s):</label>
        <select id="dogIDs" >
            <option value="CANINE001">CANINE001</option>
            <option value="CANINE002">CANINE002</option>
            <option value="CANINE003">CANINE003</option>
            <!-- Add more dog IDs as needed -->
        </select>

        <label for="metric1">Select First Metric:</label>
        <select id="metric1">
            <option value="Activity_Level">Activity Level</option>
            <option value="Heart_Rate">Heart Rate</option>
            <option value="Calorie_Burnt">Calories Burnt</option>
            <option value="Temperature">Temperature</option>
            <option value="Food_Intake">Food Intake</option>
            <option value="Water_Intake">Water Intake</option>
            <option value="Breath_Rate">Breathing Rate</option>
        </select>

        <label for="metric2">Select Second Metric:</label>
        <select id="metric2">
            <option value="Heart_Rate">Heart Rate</option>
            <option value="Activity_Level">Activity Level</option>
            <option value="Calorie_Burnt">Calories Burnt</option>
            <option value="Temperature">Temperature</option>
            <option value="Food_Intake">Food Intake</option>
            <option value="Water_Intake">Water Intake</option>
            <option value="Breath_Rate">Breathing Rate</option>
        </select>

        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate">

        <label for="endDate">End Date:</label>
        <input type="date" id="endDate">

        <button onclick="fetchComparisonData()">Compare</button>
    </div>

    <div class="chart-container">
        <canvas id="comparisonChart"></canvas>
    </div>

    <script>
        let chartInstance = null; // Store chart instance

        async function fetchComparisonData() {
            let metric1 = document.getElementById("metric1").value;
            let metric2 = document.getElementById("metric2").value;
            let startDate = document.getElementById("startDate").value;
            let endDate = document.getElementById("endDate").value;
            let selectedDogs = Array.from(document.getElementById("dogIDs").selectedOptions).map(option => option.value);

            // Validate date selection
            if (!startDate || !endDate) {
                alert("Please select a date range!");
                return;
            }

            // Validate dog selection
            if (selectedDogs.length === 0) {
                alert("Please select at least one dog.");
                return;
            }

            // Join the selected dog IDs into a comma-separated string
            let dogIDs = selectedDogs.join(',');

            // Construct the URL to fetch data
            let url = `fetch_dog_trenddata.php?start_date=${startDate}&end_date=${endDate}&dog_ids=${dogIDs}&metric1=${metric1}&metric2=${metric2}`;
            
            try {
                let response = await fetch(url);
                let data = await response.json();

                if (!Array.isArray(data) || data.length === 0) {
                    alert("No data found for the selected period.");
                    return;
                }

                updateComparisonChart(data, metric1, metric2);
            } catch (error) {
                console.error("Error fetching data:", error);
                alert("Failed to fetch data. Please try again.");
            }
        }

        function updateComparisonChart(data, metric1, metric2) {
            let dates = data.map(d => d.D_Date);
            let values1 = data.map(d => d[metric1] || 0); // Ensure fallback value
            let values2 = data.map(d => d[metric2] || 0);

            let labels = {
                "Activity_Level": "Activity Level (Steps)",
                "Heart_Rate": "Heart Rate (bpm)",
                "Calorie_Burnt": "Calories Burnt",
                "Temperature": "Temperature (°C)",
                "Food_Intake": "Food Intake (grams)",
                "Water_Intake": "Water Intake (ml)",
                "Breath_Rate": "Breathing Rate (Breaths/min)"
            };

            // Destroy existing chart instance before creating a new one
            if (chartInstance) {
                chartInstance.destroy();
            }

            let ctx = document.getElementById("comparisonChart").getContext("2d");
            chartInstance = new Chart(ctx, {
                type: "line",
                data: {
                    labels: dates,
                    datasets: [
                        {
                            label: labels[metric1],
                            data: values1,
                            borderColor: "blue",
                            backgroundColor: "rgba(0, 0, 255, 0.2)",
                            borderWidth: 2,
                            fill: true
                        },
                        {
                            label: labels[metric2],
                            data: values2,
                            borderColor: "red",
                            backgroundColor: "rgba(255, 0, 0, 0.2)",
                            borderWidth: 2,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { title: { display: true, text: "Date" } },
                        y: { title: { display: true, text: "Values" } }
                    }
                }
            });
        }
    </script>

</body>
</html>
