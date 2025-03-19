<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            text-align: center;
            padding: 25px;
            height: 100%;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .progress-circle {
            width: 140px;
            height: 140px;
            position: relative;
            display: inline-block;
            margin: auto;
        }
        .progress-circle svg {
            transform: rotate(-90deg);
        }
        .progress-circle circle {
            fill: none;
            stroke-width: 12;
            stroke-linecap: round;
        }
        .progress-background {
            stroke: #ddd;
        }
        .progress-bar {
            stroke: #3498db;
            stroke-dasharray: 314;
            stroke-dashoffset: 314;
            transition: stroke-dashoffset 1s ease-out;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 22px;
            font-weight: bold;
        }
        /* Make it mobile responsive */
        @media (max-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
            .progress-circle {
                width: 120px;
                height: 120px;
            }
            .progress-text {
                font-size: 18px;
            }
            .form-select {
                font-size: 1.2rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row text-center mb-4">
            <div class="col-md-4 col-sm-12">
                <div class="card p-3">
                    <h5>Date</h5>
                    <h3 class="text-primary">01/01/2021</h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card p-3">
                    <h5>Pet Name</h5>
                    <h3 class="text-success">Snoopy</h3>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card p-3">
                    <h5>ID</h5>
                    <h3 class="text-danger">CANINE001</h3>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card p-3">
            <h3>Select Hour</h3>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <!-- Hour buttons -->
                <button class="btn btn-outline-primary hour-btn" data-hour="0">00:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="1">01:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="2">02:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="3">03:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="4">04:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="5">05:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="6">06:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="7">07:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="8">08:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="9">09:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="10">10:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="11">11:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="12">12:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="13">13:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="14">14:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="15">15:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="16">16:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="17">17:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="18">18:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="19">19:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="20">20:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="21">21:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="22">22:00</button>
                <button class="btn btn-outline-primary hour-btn" data-hour="23">23:00</button>
            </div>
            <p class="mt-3" id="selectedHour">Selected Hour: 00:00</p>
        </div>
    </div>
</div>


<div class="row text-center">
            <!-- Weight -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="Weight_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Weight</h3>
                    <p class="fs-3 text-success" id="weight">7.2 kg</p>
                </div>
                </a>
            </div>
            <!-- Behaviour Pattern -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="behaviour_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Behaviour Pattern</h3>
                    <p class="fs-3 text-warning">Normal</p>
                </div>
                </a>
            </div>
            <!-- Barking Frequency -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="barking_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Barking Frequency</h3>
                    <p class="fs-3 text-danger">High</p>
                </div>
                </a>
            </div>
        </div>



        <div class="row text-center">
            <!-- Activity Level -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="activity_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Activity Level</h3>
                    
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="activityProgress"></circle>
                        </svg>
                        <div class="progress-text" id="activityText">0steps</div>
                    </div>
                    </a>
                </div>
            </div>

            <!-- Calorie Burn -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="calorie_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Calorie Burn</h3>
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="calorieBurnProgress"></circle>
                        </svg>
                        <div class="progress-text" id="calorieBurnText">250 kcal</div>
                    </div>
                    </a>
                </div>
            </div>

            <!-- Heart Rate -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="heart_rate_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Heart Rate</h3>
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="heartRateProgress"></circle>
                        </svg>
                        <div class="progress-text" id="heartRateText">0 bpm</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="row text-center">
            <!-- Temperature -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="temperature_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Temperature</h3>
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="tempProgress"></circle>
                        </svg>
                        <div class="progress-text" id="tempText">0°C</div>
                    </div>
                    </a>
                </div>
            </div>

    
            <!-- Breathing Rate -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="breathing_rate_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Breathing Rate</h3>
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="breathingRateProgress"></circle>
                        </svg>
                        <div class="progress-text" id="breathingRateText">30 b/min</div>
                    </div>
                </div>
                </a>
            </div>
        </div>

        
        <div class="row text-center">
            
            <!-- Food Intake -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="food_intake_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Food Intake</h3>
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="foodIntakeProgress"></circle>
                        </svg>
                        <div class="progress-text" id="foodIntakeText">500 kcal</div>
                    </div>
                    </a>
                </div>
            </div>
            <!-- Water Intake -->
            <div class="col-md-4 col-sm-12 mb-4">
            <a href="water_intake_chart.php" class="text-decoration-none">
                <div class="card">
                    <h3>Water Intake</h3>
                    <div class="progress-circle">
                        <svg width="140" height="140">
                            <circle cx="70" cy="70" r="60" class="progress-background"></circle>
                            <circle cx="70" cy="70" r="60" class="progress-bar" id="waterIntakeProgress"></circle>
                        </svg>
                        <div class="progress-text" id="waterIntakeText">800 ml</div>
                    </div>
                </div>
                
            </div>
        </div>
        </a>
    </div>

    <script>$(document).ready(function () {
    let selectedHour = null; // Store the selected hour

    function fetchDogData() {
        let requestUrl = "fetch_dog_data.php";
        if (selectedHour !== null) {
            requestUrl += "?hour=" + selectedHour;
        }

        console.log("Fetching data from:", requestUrl);

        $.ajax({
            url: requestUrl,
            method: "GET",
            dataType: "json",
            success: function (data) {
                console.log("Received data:", data);

                if (data.length > 0) {
                    let dogData = data[0];

                    $("#selectedHour").text("Selected Hour: " + dogData.hour + ":00");

                    setProgress("activityProgress", "activityText", dogData.activity_level, 5000, " steps");
                    setProgress("heartRateProgress", "heartRateText", dogData.heart_rate, 150, " bpm");
                    setProgress("tempProgress", "tempText", dogData.temperature, 50, "°C");
                    setProgress("breathingRateProgress", "breathingRateText", dogData.breathing_rate, 50, " br/min");
                    setProgress("calorieBurnProgress", "calorieBurnText", dogData.calorie_burn, 500, " kcal");
                    setProgress("foodIntakeProgress", "foodIntakeText", dogData.food_intake, 800, " kcal");
                    setProgress("waterIntakeProgress", "waterIntakeText", dogData.water_intake, 1000, " ml");

                    $("#weight").text(dogData.weight + " kg");
                    $(".text-warning").text(dogData.behaviour);
                    $(".text-danger").text(dogData.barking_frequency);
                } else {
                    console.log("No data found for this hour.");
                    alert("No data available for this hour.");
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    }

    // Call function when page loads
    fetchDogData();

    // Auto-refresh every 10 seconds
    setInterval(fetchDogData, 10000);

    // Handle hour button clicks
    $(".hour-btn").click(function () {
        $(".hour-btn").removeClass("btn-primary"); // Remove highlight from all buttons
        $(this).addClass("btn-primary"); // Highlight selected button

        selectedHour = $(this).data("hour"); // Store the selected hour
        fetchDogData(); // Fetch new data
    });
});

// Function to update progress bars
function setProgress(elementId, textId, value, max, unit) {
    let percentage = (value / max) * 314;
    $("#" + elementId).css("stroke-dashoffset", 314 - percentage);
    $("#" + textId).text(value + unit);
}

</script>

</body>
</html>     