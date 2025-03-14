<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification Test Page</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        input {
            padding: 10px;
            margin: 10px;
            width: 200px;
            font-size: 16px;
        }
        button {
            padding: 10px 15px;
            margin: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        #notification {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: red;
            color: white;
            padding: 15px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h2>JavaScript Notification Test</h2>
    
    <label>Enter Heart Rate (bpm): </label>
    <input type="number" id="heartRateInput" placeholder="e.g. 130">
    <button onclick="checkHeartRate()">Check Heart Rate</button>

    <label>Enter Temperature (°C): </label>
    <input type="number" id="tempInput" placeholder="e.g. 40">
    <button onclick="checkTemperature()">Check Temperature</button>

    <label>Enter Water Intake (ml): </label>
    <input type="number" id="waterInput" placeholder="e.g. 300">
    <button onclick="checkWaterIntake()">Check Water Intake</button>

    <div id="notification">⚠️ High Temperature Alert!</div>

    <script>
        // Function to request browser notification permission
        function requestNotificationPermission() {
            if (Notification.permission !== "granted") {
                Notification.requestPermission();
            }
        }

        // Function to show a browser notification
        function showBrowserNotification(title, message) {
            if (Notification.permission === "granted") {
                new Notification(title, {
                    body: message,
                    icon: "https://cdn-icons-png.flaticon.com/512/565/565515.png"
                });
            } else {
                requestNotificationPermission();
            }
        }

        // Function to show in-app notification
        function showInAppNotification(message) {
            let notification = document.getElementById("notification");
            notification.innerText = message;
            notification.style.display = "block";

            setTimeout(() => {
                notification.style.display = "none";
            }, 3000);
        }

        // Function to show SweetAlert2 pop-up
        function showSweetAlert(title, message, icon) {
            Swal.fire({
                title: title,
                text: message,
                icon: icon,
                confirmButtonText: "Got it"
            });
        }

        // Check heart rate and trigger notification
        function checkHeartRate() {
            let heartRate = document.getElementById("heartRateInput").value;
            if (heartRate > 120 || heartRate < 50) {
                showSweetAlert("⚠️ Heart Rate Alert!", `Heart rate abnormal: ${heartRate} bpm`);
            } else {
                showSweetAlert("✅ Normal Heart Rate", `Heart rate is fine: ${heartRate} bpm`, "success");
            }
        }

        // Check temperature and trigger notification
        function checkTemperature() {
            let temp = document.getElementById("tempInput").value;
            if (temp > 39) {
                showSweetAlert("🔥 High Temperature Alert! " + temp + "°C");
            } else {
                showSweetAlert("✅ Normal Temperature", `Temperature is normal: ${temp}°C`, "success");
            }
        }

        // Check water intake and trigger notification
        function checkWaterIntake() {
            let waterIntake = document.getElementById("waterInput").value;
            if (waterIntake < 500) {
                showSweetAlert("🚰 Hydration Alert!", `Water intake is too low: ${waterIntake} ml`, "warning");
            } else {
                showSweetAlert("✅ Good Hydration", `You're drinking enough water! ${waterIntake} ml`, "success");
            }
        }

        // Request notification permission on page load
        requestNotificationPermission();
    </script>

</body>
</html>
