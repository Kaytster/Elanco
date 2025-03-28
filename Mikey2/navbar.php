<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Data</title>
    <style>
        
        nav {
            background-color: #0067B1;
            color: #fff;
            position: fixed;
            height: 100%;
            width: 150px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        nav img 
        {
            width: 100px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        nav .logout 
        {
            margin-top: 31em;
            margin-left: -20px;
        }
        nav  img 
        {
            width: 105px;
            margin-left: 20px;
        }
        nav .logout img
        {
            width: 15px;
            margin-right: 20px;
        }
        nav a:hover {
            background-color: #82ADCC;
        }
        .container {
            margin-left: 220px;
            padding: 20px;
        }
        nav .logo img
        {
            width: 100px;
        }
    </style>
</head>
<body>
    <nav>
        <img src="Elanco.png" alt="Elanco">
        <a href="javascript:history.back()">Previous Page</a>
        <a href="landingpage.php">My Pets</a>
        <a href="AdminLogin.php" class="logout"><img src="logout.png" alt="">LogOut</a>
    </nav>
    <div class="container">
</body>
</html>
