<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #0067B1, #004B8D);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        
        .logout-container {
            text-align: center;
            color: white;
        }
        
        .circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            position: relative;
            animation: pulse 1.5s infinite, fadeOut 2s 1.5s forwards;
        }
        
        .circle::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid white;
            animation: spin 2s linear infinite;
        }
        
        .circle i {
            font-size: 30px;
            color: white;
            animation: fadeOut 2s 1.5s forwards;
        }
        
        h2 {
            margin: 10px 0;
            animation: fadeOut 2s 1.5s forwards;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.5);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }
        
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
        
        @keyframes fadeOut {
            0% {
                opacity: 1;
            }
            100% {
                opacity: 0;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="logout-container">
        <div class="circle">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h2>Logging out...</h2>
    </div>
    
    <script>
        // Redirect after animation completes
        setTimeout(function() {
            window.location.href = 'AdminLogin.php';
        }, 3000);
    </script>
</body>
</html>