<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];  
    $userpsswrd = $_POST['userpsswrd'];
    

    if (empty($email) || empty($userpsswrd)) {
        
        header("Location: index.php?error=empty_fields");
        exit();
    }
    

    $db = new SQLite3('Elanco-Final.db');
    

    $stmt = $db->prepare('SELECT UserPsswrd FROM USER WHERE email = :email');
    $stmt->bindValue(':email', $email, SQLITE3_TEXT); 
    $result = $stmt->execute();
    
    
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if($user)
    {
        $userpassowrd = $user['UserPsswrd'];
        $useremail = $user['email'];

        if($userpassowrd === $userpsswrd)
        {
            $_SESSION['email'] = $email;
            $_SESSION['useremail'] = $useremail;
            header("Location:landingpage.php");
            exit();
        }
        else
        {
            echo "Incorrect password";
            exit();
        }
    }
    else
    {
        echo "User not found";
    }
    
    
    
}