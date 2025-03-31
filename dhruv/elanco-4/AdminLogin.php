<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Pet Health Tracker - Login</title>
    <style>
        :root {
            --primary: #0067B1;
            --primary-light: #82ADCC;
            --primary-dark: #004B8D;
            --secondary: #E8DEF8;
            --accent: #FFD166;
            --text-dark: #333333;
            --text-light: #FFFFFF;
            --background: #f0f8ff;
            --card-bg: #FFFFFF;
            --success: #06D6A0;
            --warning: #FFD166;
            --danger: #EF476F;
            --shadow: 0 10px 30px rgba(0, 103, 177, 0.15);
            --radius: 16px;
            --transition: all 0.3s ease;
            --gradient: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }
        
        body {
            background: linear-gradient(135deg, #e0f2ff 0%, #f0f8ff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%230067b1' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
            z-index: -1;
        }
        
        .main-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            width: 100%;
            max-width: 1000px;
            min-height: 600px;
            background-color: var(--card-bg);
            border-radius: var(--radius);
            overflow: hidden;
            display: flex;
            box-shadow: var(--shadow);
            position: relative;
        }
        
        .login-side {
            flex: 1;
            padding: 50px;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .image-side {
            flex: 1;
            overflow: hidden;
            position: relative;
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0067B1 0%, #004B8D 100%);
        }
        
        .image-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        
        .blob-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.6;
            transform: scale(1.1);
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 600 600' xmlns='http://www.w3.org/2000/svg'%3E%3Cg transform='translate(300,300)'%3E%3Cpath d='M145.4,-189.3C193.6,-161.9,241.9,-128.3,251.6,-85.7C261.4,-43.1,232.6,-8.5,208.7,20.9C184.8,50.3,166,74.4,142.5,99.7C118.9,125,90.8,151.4,56.6,166.4C22.4,181.5,-17.9,185.3,-58.3,177.7C-98.7,170.1,-139.2,151.1,-162.9,122.1C-186.6,93.1,-193.5,54.2,-202,12.9C-210.4,-28.4,-220.5,-72.2,-207.4,-108.9C-194.3,-145.7,-158.1,-175.5,-118.6,-205.4C-79.1,-235.3,-36.3,-265.3,5.3,-272.3C46.9,-279.3,97.2,-216.7,145.4,-189.3Z' fill='%2382adcc'/%3E%3C/g%3E%3C/svg%3E");
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            animation: rotate 20s linear infinite;
        }
        
        @keyframes rotate {
            0% {
                transform: scale(1.1) rotate(0deg);
            }
            100% {
                transform: scale(1.1) rotate(360deg);
            }
        }
        
        .pet-image {
            position: relative;
            z-index: 1;
            width: 80%;
            max-width: 500px;
            filter: drop-shadow(0 8px 24px rgba(0, 0, 0, 0.3));
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        .logo-container {
            position: absolute;
            top: 30px;
            left: 50px;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 15px;
            border-radius: 12px;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .logo-container:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }
        
        .logo {
            max-width: 140px;
            opacity: 1;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            animation: logo-pulse 3s ease-in-out infinite;
        }
        
        @keyframes logo-pulse {
            0%, 100% {
                filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            }
            50% {
                filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.8));
            }
        }
        
        .form-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-dark);
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
        }
        
        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--gradient);
            border-radius: 50px;
        }
        
        .form-subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
            max-width: 90%;
        }
        
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-field {
            width: 100%;
            padding: 18px 15px;
            border: 2px solid rgba(0, 103, 177, 0.1);
            border-radius: 12px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            padding-left: 50px;
            color: var(--primary-dark);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }
        
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(0, 103, 177, 0.1);
            outline: none;
            background-color: white;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 20px;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        
        .input-field:focus + .input-icon {
            color: var(--primary-dark);
        }
        
        .form-group label {
            position: absolute;
            top: -10px;
            left: 15px;
            background-color: white;
            padding: 0 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            border-radius: 4px;
            z-index: 1;
        }
        
        .login-button {
            position: relative;
            padding: 16px 30px;
            border: none;
            background: var(--gradient);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 20px -3px rgba(0, 103, 177, 0.3);
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            z-index: -1;
            transition: left 0.7s ease;
        }
        
        .login-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px -3px rgba(0, 103, 177, 0.4);
        }
        
        .login-button:hover::before {
            left: 100%;
        }
        
        .login-button:active {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(0, 103, 177, 0.4);
        }
        
        .login-button i {
            font-size: 18px;
        }
        
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            display: block;
            pointer-events: none;
            z-index: 1;
            border-radius: 50%;
        }
        
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 600px;
            }
            
            .image-side {
                min-height: 300px;
                order: -1;
            }
            
            .login-side {
                padding: 40px 30px;
            }
            
            .logo-container {
                top: 20px;
                left: 20px;
            }
            
            .logo {
                max-width: 100px;
            }
        }
        
        @media (max-width: 576px) {
            .login-side {
                padding: 30px 20px;
            }
            
            .form-title {
                font-size: 28px;
            }
            
            .image-side {
                min-height: 220px;
            }
            
            .pet-image {
                width: 70%;
            }
        }

        /* Animation for particles */
        @keyframes float-particle {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-15px) translateX(15px);
            }
            50% {
                transform: translateY(-25px) translateX(0);
            }
            75% {
                transform: translateY(-15px) translateX(-15px);
            }
        }

        .brand-watermark {
            position: absolute;
            bottom: 30px;
            right: 30px;
            z-index: 2;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        
        .brand-watermark:hover {
            opacity: 1;
        }
        
        .watermark-logo {
            max-width: 120px;
            filter: brightness(0) invert(1);
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.5s ease;
        }
        
        .watermark-logo.appear {
            opacity: 0.7;
            transform: scale(1);
        }
        
        .logo-container.spotlight {
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 25px 5px rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="login-container">
            <div class="login-side">
                <div class="form-content">
                    <h1 class="form-title">Welcome Back</h1>
                    <p class="form-subtitle">Please log in to access your pet's health information and monitoring dashboard.</p>
                    
                    <form action="LogInAction.php" method="post">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" id="email" name="email" required placeholder="Enter your email" class="input-field">
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                        
                        <div class="form-group">
                            <label for="userpsswrd">Password</label>
                            <input type="password" id="userpsswrd" name="userpsswrd" required placeholder="Enter your password" class="input-field">
                            <i class="fas fa-lock input-icon"></i>
                        </div>
                        
                        <button type="submit" class="login-button">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login to Dashboard</span>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="image-side">
                <div class="logo-container">
                    <img src="Elanco.png" alt="Elanco Logo" class="logo">
                </div>
                
                <div class="image-content">
                    <div class="blob-bg"></div>
                    <img src="dogpic.png" alt="Pet Health Illustration" class="pet-image">
                    <div class="brand-watermark">
                        <img src="Elanco.png" alt="Elanco" class="watermark-logo">
                    </div>
                </div>
                
                <div class="particles" id="particles"></div>
            </div>
        </div>
    </div>
    
    <script>
        // Create particles
        const particlesContainer = document.getElementById('particles');
        const particleCount = 15;
        
        for (let i = 0; i < particleCount; i++) {
            createParticle();
        }
        
        function createParticle() {
            const particle = document.createElement('span');
            particle.classList.add('particle');
            
            // Random size between 4 and 12
            const size = Math.random() * 8 + 4;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Random position
            const posX = Math.random() * 100;
            const posY = Math.random() * 100;
            particle.style.left = `${posX}%`;
            particle.style.top = `${posY}%`;
            
            // Random opacity and color
            const opacity = Math.random() * 0.5 + 0.1;
            const hue = Math.random() * 20 + 190; // Blue-ish
            particle.style.backgroundColor = `hsla(${hue}, 80%, 70%, ${opacity})`;
            
            // Random animation duration and delay
            const duration = Math.random() * 20 + 10;
            const delay = Math.random() * 10;
            particle.style.animation = `float-particle ${duration}s ease-in-out ${delay}s infinite`;
            
            particlesContainer.appendChild(particle);
        }
        
        // Add spotlight effect to the logo
        const logoContainer = document.querySelector('.logo-container');
        
        // Add initial spotlight class to draw attention
        setTimeout(() => {
            logoContainer.classList.add('spotlight');
            
                }, 500);
        
        // Add class for the watermark logo animation
        const watermarkLogo = document.querySelector('.watermark-logo');
        setTimeout(() => {
            watermarkLogo.classList.add('appear');
        }, 1000);
    </script>
</body>
</html>