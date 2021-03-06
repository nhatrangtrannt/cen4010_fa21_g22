<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then direct to welcome page
if (isset($_SESSION["loggedin"])){
    if ($_SESSION["role"] == "admin"){
        header("Location: class/Admin.php");
    } elseif ($_SESSION["role"] == "doctor"){
        header("Location: class/Doctor.php");
    } elseif ($_SESSION["role"] == "patient"){
        header("Location: class/Patient.php");
    }
    //header("location: welcome.php");
    exit;
}
 
// Include config file and function file
require_once "config/config.php";
require_once "functions/loginfunc.php";
// Initialize empty variables to check error
$usernameError = $passwordError = $loginError = $roleError = "";

// Processing data when submit button is pressed
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
 
    // Validate username
    if (empty(trim($_POST["username"]))){
        $usernameError = "Username is required";
    }
    
    // Validate password
    if (empty(trim($_POST["password"]))){
        $passwordError = "Passoword is required";
    } 

    // Validate role
    if (empty($_POST["role"])){
        $loginError = "Role is required";
    }

    
    // If there is no error, then check if username is in database. If yes, then check password that is hashed
    if ($usernameError == "" && $passwordError == "" && $loginError == ""){
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
                            header("Location: class/Admin.php");
                        } elseif ($role == "doctor"){
                            header("Location: class/Doctor.php");
                        } elseif ($role == "patient"){
                            header("Location: class/Patient.php");
                        }
                    }      
                    else{
                        // password not match
                        $loginError = "Incorrect username or password";
                    }
                }
            }else{
                $loginError = "Incorrect username or password";
                    
            }
            // Close statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);       
        }
    }
        
}
    
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body{ 
            font: 14px sans-serif; 
        }
        .wrapper{ 
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            align-content: center;
        }
        .container {
            height: 500px;
            width: 600px;
            position: relative;
            border: 10px solid rgb(128, 209, 236);
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($loginError)){
            echo '<div class="alert alert-danger">' . $loginError . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $usernameError; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($passwordError)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $passwordError; ?></span>
            </div>
            <div class="form-group">
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" value="admin">
                <label class="form-check-label">Admin</label>
                </div>
            
                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" value="doctor">
                <label class="form-check-label">Doctor</label>
                </div>

                <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="role" value="patient">
                <label class="form-check-label">Patient</label>
                </div>
            </div>
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
    </div>
</body>
</html>