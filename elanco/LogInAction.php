<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];  
    $password = $_POST['password'];
    

    if (empty($username) || empty($password)) {
        
        header("Location: index.php?error=empty_fields");
        exit();
    }
    

    $db = new SQLite3('dog_data.db');
    

    $stmt = $db->prepare('SELECT * FROM USER WHERE User = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT); 
    $result = $stmt->execute();
    
    
    $user = $result->fetchArray(SQLITE3_ASSOC);
    
    
    if (!$user) {
        echo "User not found in database";
        exit();
    }
    
    
    $passwordCorrect = false;
    
    if (isset($user['Password'])) {
        
        if (password_verify($password, $user['Password'])) {
            $passwordCorrect = true;
        } 
 
        else if ($password === $user['Password']) {
            $passwordCorrect = true;
        }
    }
    
    if ($passwordCorrect) {
        
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        
        
        header("Location: dashboard.php");
        exit();
    } else {
        
        header("Location: index.php?error=invalid_credentials");
        exit();
    }
    

    $db->close();

} else {
   
    header("Location: index.php");
    exit();
}