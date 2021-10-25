<?php
// Initialize the session
session_start();
 
// Check if the user is logged in as a doctor, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] == 'patient'){
    header("location: index.html");
    exit;
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body{ 
            font: 14px sans-serif; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <h1 class="my-5">Hello <?php $username = $_SESSION['username']; echo "$username,"; ?> Welcome to our application.</h1>
    <p>
        <a href="../logout.php" class="btn btn-danger">Sign Out</a>
    </p>
</body>
</html>