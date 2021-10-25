<?php
// Include config 
require_once "config/config.php";
 
# If submit button is pressed, then validate the input
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    # Obtain user's input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPass'];    
    
    # Initialize empty variables to check error
    $usernameError = $passwordError = $confirmPasswordErr = "";

    // Validate username
    if (empty(trim($_POST["username"]))){
        $usernameError = "Username is required";
    }elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $usernameError = "Username is not valid";
    } 
    
    // Validate password and confirm pass
    if (empty(trim($_POST["password"]))){
        $passwordError = "Password is required";
    }
    if (empty(trim($_POST["confirmPass"]))){
        $confirmPasswordErr = "Confirm Password is required";
    }
    if ($password !== $confirmPass){
        $confirmPasswordErr = "Confirm Password does not match";
    }
    
    // If there is no error, then check if username exists in the database. If everything is good, then push data to database
    if ($usernameError == "" && $passwordError == "" && $confirmPasswordErr == "") {
    // SELECT statement
        $sql = "SELECT username FROM patient WHERE username = ?";
        $stmt = mysqli_stmt_init($conn);
        # If connection fails
        if (!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: register.php?error=sqlerror");
            exit();
        }else {
            // Bind variables to the prepared statement 
            mysqli_stmt_bind_param($stmt, "s", $username);
            // Execute prepared statement
            mysqli_stmt_execute($stmt);
            // Store result
            mysqli_stmt_store_result($stmt);
            // Count rows
            $rowCount = mysqli_stmt_num_rows($stmt);
            // Check if username is already existed
            if ($rowCount > 0){
                $usernameError = "Username is taken";
            } else { 
                // If there is no error, hash password 
                $username = trim($_POST["username"]);
                $password = trim($_POST["password"]);
                $hashedpassword = password_hash($password, PASSWORD_DEFAULT); 
                // Store in the database 
                $sql = "INSERT INTO patient (username, password) VALUES (?, ?)";
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: register.php?error=sqlerror");
                    exit();
                } else {
                    // Bind variables
                    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedpassword);
                    // Execute prepared statement   
                    mysqli_stmt_execute($stmt);
                    // Direct to Login page 
                    header("Location: login.php");
                    // Close statement and connection
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);
                    
                }
               
            }
        }
    }   
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
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
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <?php       
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
                <label>Confirm Password</label>
                <input type="password" name="confirmPass" class="form-control <?php echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirmPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
    </div>
</body>
</html>