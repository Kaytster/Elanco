<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Data</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Global layout styles -->
    <link rel="stylesheet" href="layout.css">
    <style>
        :root {
            --primary: #0067B1;
            --primary-light: #82ADCC;
            --primary-dark: #004B8D;
            --text-light: #FFFFFF;
        }
        
        nav {
            background: linear-gradient(to bottom, var(--primary), var(--primary-dark));
            color: var(--text-light);
            position: fixed;
            height: 100%;
            width: 180px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        nav img {
            width: 120px;
            margin: 20px auto;
            display: block;
            transition: transform 0.3s ease;
        }

        nav img:hover {
            transform: scale(1.05);
        }

        nav a {
            color: var(--text-light);
            text-decoration: none;
            padding: 15px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 500;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        nav a i {
            font-size: 1.1rem;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 3px;
            height: 100%;
            background-color: var(--text-light);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        nav a:hover::before {
            transform: scaleY(1);
        }

        nav .logout {
            margin-top: auto;
            margin-bottom: 20px;
        }

        nav .logout img {
            width: 15px;
            margin-right: 10px;
            display: inline-block;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        
        nav a.active {
            background-color: rgba(255, 255, 255, 0.2);
            border-left: 3px solid white;
        }

        /* Responsive navbar */
        @media (max-width: 992px) {
            nav {
                width: 60px;
            }
            
            nav a span {
                display: none;
            }
            
            nav a i {
                margin-right: 0;
                font-size: 1.3rem;
            }
        }
        
        @media (max-width: 576px) {
            nav {
                width: 50px;
            }
            
            nav img {
                width: 40px;
                margin: 15px auto;
            }
            
            nav a {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <img src="Elanco.png" alt="Elanco">
        <a href="landingpage.php"><i class="fas fa-home"></i> <span>Home</span></a>
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
        <a href="trends.php"><i class="fas fa-chart-line"></i> <span>Trends</span></a>
        <a href="javascript:history.back()"><i class="fas fa-arrow-left"></i> <span>Back</span></a>
        <a href="AdminLogin.php" class="logout"><i class="fas fa-sign-out-alt"></i> <span>LogOut</span></a>
    </nav>
    <div class="container">
</body>
</html>
