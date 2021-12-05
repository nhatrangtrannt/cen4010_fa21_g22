<?php
// Initialize the session
session_start();
 
 // Check if the user is already logged in, if yes then direct to welcome page
if (isset($_SESSION["loggedin"])){
    if ($_SESSION["role"] == "admin"){
        header("Location: admin/admin.php");
    } elseif ($_SESSION["role"] == "doctor"){
        header("Location: doctor/doctor.php");
    } elseif ($_SESSION["role"] == "patient"){
        header("Location: patient/patient.php");
    }
    //header("location: welcome.php");
    exit;
}
 
// Include config file and function file
require_once "config/config.php";
require_once "functions/loginfunc.php";


// Processing data when submit button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);
 

    // Check role to look up in the database
    $loginTable = login($role);
    // SELECT statement
    $sql = "SELECT username,password FROM ".$loginTable." WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);
    // If connection fails
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: login.php?error=sqlerror");
        exit();
    } else {
        // Bind variables to the prepared statement 
        mysqli_stmt_bind_param($stmt, "s",$username);
        // Execute prepared statement
        mysqli_stmt_execute($stmt);
        // Store result
        $result = mysqli_stmt_store_result($stmt);
        // Count rows
        $rowCount = mysqli_stmt_num_rows($stmt);
        
        // Check if username is already existed
        if ($rowCount == 1){
            // Bind values in result to variables
            mysqli_stmt_bind_result($stmt, $username, $hashedpassword);
                // Store result
            mysqli_stmt_store_result($stmt);
            // Check if data is fetched 
            if (mysqli_stmt_fetch($stmt)) {
                if(password_verify($password,$hashedpassword)) {
                    session_start();
                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $username; 
                    $_SESSION["role"] = $role; 
                    // Direct user to welcome page depending on role
                    if ($role == "admin"){
                        header("Location: admin/admin.php");
                    } elseif ($role == "doctor"){
                        header("Location: doctor/doctor.php");
                    } elseif ($role == "patient"){
                        header("Location: patient/patient.php");
                    }
                }      
                else{
                    // password not match
                    $loginError = "Incorrect username or password";
                }
            }
        }else{
            $loginError = "Incorrect username or password0";    
        }
        // Close statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);       
    }      
}    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="assets/fonts1/material-icon/css/material-design-iconic-font.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css1/style.css">
</head>
<body>
    <div class="main">
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="signup-form" class="signup-form">
                        <h2 class="form-title">Login</h2>
                        <?php 
                            if(!empty($loginError)){
                                echo '<span style="color:red;" class="invalid-feedback">'.$loginError.'</span>';
                            }        
                            if (isset($_GET['success'])){
                                echo
                                    '<span style="color:green;" class="text-center">'.$_SESSION['success'].'</span>';
                                    unset($_SESSION['success']);
                            }
                        ?>
 <!--Account info-->    <h1 class="form-title">Account information</h1>
                        <div class="form-group">
                            <input type="text" class="form-input" name="username" placeholder="User Name" value="<?php if (isset($username)) echo $username;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <input class="form-check-input" name="role" value="admin" type="radio" required>
                            <label class="form-check-label" for="inlineRadio1" >Admin</label> 
                            <input class="form-check-input" name="role" value="doctor" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >Doctor</label>
                            <input class="form-check-input" name="role" value="patient" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >Patient</label>
                        </div>
                        <div class="form-group">
                            <input type="submit" class="form-submit" value="Login"/>
                        </div>
                        <p class="loginhere">
                            Do not have an account ? <a href="register.php" class="loginhere-link">Sign up here</a>
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- JS -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
<style>
    body{
        margin: 10;
        padding-top: 40px;
        color: #002D62;
        background-color: #63a4ff;
        background-image: linear-gradient(315deg, #63a4ff 0%, #83eaf1 74%);
        position: relative;
        height: 100%;
    }
</style>
</html>
    