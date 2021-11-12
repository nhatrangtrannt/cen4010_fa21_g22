<?php
session_start();
require_once "../config/config.php";
require_once "../functions/loginfunc.php";

# If submit button is pressed, then validate the input
if (isset($_POST['submit'])){
    # Obtain user's input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPass'];  
    $role = $_POST['role'];
    $table = login($role);

   
    # Initialize empty variables to check error
    $usernameError = $passwordError = $confirmPasswordErr = $phoneError = "";

    // Validate username
    if (empty(trim($_POST["username"]))){
        $usernameError = "Username is required";
    }elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $usernameError = "Username is not valid";
    }
    $sql = "SELECT * FROM ".$table." WHERE username = '$username'";
    $stmt = mysqli_query($conn,$sql);

    if (mysqli_num_rows($stmt) > 0){
        $usernameError = "Username is taken.";
    }
        


    //$sql = "SELECT * FROM" .$table. "WHERE username = '$username'";
    //$sql = "SELECT username FROM" .$table. "WHERE username = '$username'";

    //$result = mysqli_query($conn,$sql);
    //$row = mysqli_fetch_array($result);
    //if ($row) {
        
    //        $usernameError = "Username is taken."; 
        
    //}

    
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
    
     // Validate role
    if (empty($_POST["role"])){
        $loginError = "Role is required";
    }
  
    // If there is no error, then check if username exists in the database. If everything is good, then push data to database
    if ($usernameError == "" && $passwordError == "" && $confirmPasswordErr == "" && $loginError == "") {
        
        
       
       /* require_once "../config/config.php";

        $sql = "SELECT * FROM ".$table." WHERE username = '$username'";
        $stmt = mysqli_query($conn,$sql);

        if (mysqli_num_rows($stmt) > 0){
            $usernameError = "Username is taken.";

        }
        else{*/
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);     
    
            $sql = "INSERT INTO ".$table."(username, password) VALUES ('$username','$hashedpassword')";
            $stmt = mysqli_query($conn,$sql);
            if($stmt){
                $_SESSION['success'] = "Successfully insert ".$username; 
                
            }
            else{
                $_SESSION['error'] = "Fail to insert".$username;
            }
            
    
        
        header("location: ../class/Admin.php");
        //header("location: ..")
        
                
            
            
        
        
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
        <h2>Update</h2>
        <p>Please fill this form to update your account.</p>
        <?php 
        if(!empty($loginError)){
            echo '<div class="alert alert-danger">' . $loginError . '</div>';
        }        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?php if (isset($username)) echo $username;?>" name="username" class="form-control <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>">
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
                <input type="submit" class="btn btn-primary" name ="submit" value="Update">
                <input type="button" class="btn btn-primary" value="Go back!" onclick="history.back()">
            </div>
            
        </form>
    </div>    
</div>
    
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>