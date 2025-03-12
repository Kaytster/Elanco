<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="index.css">
    <title>ELANCO</title>
</head>
<body>
    
  <header>
     <img src="Elanco.png" alt="Elanco Logo">
  </header>    

  <div class="welcome-message">
    <h3>Welcome, Pet Owner</h3>
  </div>
    
  <div class="login">
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit" class="submit-button">Login</button>
    </form>
  </div>

  <div class="user-login">
    <a href="AdminLogin.php"><button type="button" class="admin-button">Admin Login</button></a>
  </div>

  <div class="dog-picture">
    <img src="dogpic.png" alt="Dog Picture">
  </div>


</body>
</html>