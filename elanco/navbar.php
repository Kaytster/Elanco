<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            display: flex;
            justify-content: flex-start;
        }
        nav {
            background-color: #0067B1;
            color: #fff;
            padding: 20px;
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
        }
        nav a img 
        {
            width: 15px;
            margin-right: 20px;
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
    </style>
</head>
<body>
    <nav>
        <img src="Elanco.png" alt="">
        <a href="javascript:history.back()">Previous Page</a>
        <a href="mypets.php">My Pets</a>
        <a href="#" class="logout"><img src="logout.png" alt="">LogOut</a>
    </nav>
    <div class="container">
</body>
</html>
