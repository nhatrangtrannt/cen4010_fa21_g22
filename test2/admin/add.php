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
    $usernameError = $passwordError = $confirmPasswordErr = "";

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
        
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);     
    
            $sql = "INSERT INTO ".$table."(username, password) VALUES ('$username','$hashedpassword')";
            $stmt = mysqli_query($conn,$sql);
            if($stmt){
                $_SESSION['success'] = "Successfully insert ".$username; 
                
            }
            else{
                $_SESSION['error'] = "Fail to insert".$username;
            }
            
    
        
        header("location: ../admin/admin.php");
        //header("location: ..")
        
        
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sign Up</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="../assets/fonts1/material-icon/css/material-design-iconic-font.min.css">

    <!-- Main css -->
    <link rel="stylesheet" href="../assets/css1/style.css">

</head>
<body>

    <div class="main">

        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="signup-form" class="signup-form">
                        
                        <h2 class="form-title">Create a new account</h2>
                            <?php 
                                if(!empty($loginError)){
                                    echo '<span style="color:red;" class="invalid-feedback">'.$loginError.'</span>';
                                    echo "\n";
                                }        
                            
                                if(!empty($usernameError)){
                                    echo '<span style="color:red;" class="invalid-feedback">'.$usernameError.'</span>';
                                    echo "\n";
                                }        
                                if(!empty($passwordError)){
                                    echo '<span style="color:red;" class="invalid-feedback">'.$passwordError.'</span>';
                                    echo "\n";
                                }    
                                if(!empty($confirmPasswordErr)){
                                    echo '<span style="color:red;" class="invalid-feedback">'.$confirmPasswordErr.'</span>';
                                    echo "\n";
                                }            
                            ?>
                        <!--Account info--> 
                        <h1 class="form-title">Account information</h1>
                            <div class="form-group">
                                <input type="text" class="form-input" name="username" placeholder="User Name" value="<?php if (isset($username)) echo $username;?>" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-input" name="password" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-input" name="confirmPass" placeholder="Confirm Password" required>
                            </div>
                            <div class="form-group">
                                <input class="form-check-input" name="role" value="doctor" type="radio" required>
                                <label class="form-check-label" for="inlineRadio1" >Doctor</label>
                                <input class="form-check-input" name="role" value="patient" type="radio" >
                                <label class="form-check-label" for="inlineRadio1" >Patient</label>
                            </div>                      
                            <div class="form-group">
                                <input type="submit" class="form-submit" name ="submit" value="Create">
                            </div>
                            <div class="form-group">
                                <input type="button" class="form-submit" value="Go back!" onclick="history.back()">
                            </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
       <!-- JS -->
       <script src="../assets/vendor/jquery/jquery.min.js"></script>
    
    </body><!-- This templates was made by Colorlib (https://colorlib.com) -->
        
        
    <style>
         body {
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