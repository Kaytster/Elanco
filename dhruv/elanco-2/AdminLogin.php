<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="AdminLogin.css">
    <title>Pet Health Tracker - Login</title>
    <style>
        /* UIverse-inspired login input styles */
        .form-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-field {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid rgba(0, 103, 177, 0.2);
            border-radius: 12px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
            padding-left: 45px;
            color: var(--primary-dark);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .input-field:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 103, 177, 0.15);
            outline: none;
            background-color: white;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
            font-size: 18px;
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
        
        .welcome-message h3 {
            position: relative;
            display: inline-block;
            margin-bottom: 30px;
        }
        
        .welcome-message h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--gradient);
            border-radius: 10px;
        }
        
        .login-button {
            position: relative;
            padding: 14px 30px;
            border: none;
            background: var(--gradient);
            color: white;
            border-radius: 50px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 15px -3px rgba(0, 103, 177, 0.2);
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
        }
        
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.7s ease;
        }
        
        .login-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 20px -3px rgba(0, 103, 177, 0.3);
        }
        
        .login-button:hover::before {
            left: 100%;
        }
        
        .login-button:active {
            transform: translateY(0);
            box-shadow: 0 5px 10px -3px rgba(0, 103, 177, 0.3);
        }
        
        .logo-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }
        
        .login-card {
            background-color: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
            margin-top: 20px;
        }
        
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
        }
    </style>
</head>
<body>
    
  <header>
     <img src="Elanco.png" alt="Elanco Logo" class="fade-in logo-animation">
  </header>    

  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6">
        <div class="welcome-message">
          <h3>Welcome to Pet Health Tracker</h3>
          <p>Please log in to access your pet's health information and monitoring dashboard. Track vital signs, activity levels, and more in real-time.</p>
        </div>
        
        <div class="login">
          <div class="login-card">
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
      </div>
      
      <div class="col-lg-6">
        <div class="dog-picture">
          <img src="dogpic.png" alt="Dog Picture">
        </div>
      </div>
    </div>
  </div>
</body>
</html>