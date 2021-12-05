<?php
session_start();
// Include config 
require_once "config/config.php";
 
# If submit button is pressed, then validate the input
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    # Obtain user's input
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPass']);    
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $gender = trim($_POST['gender']);
    $birthday = trim($_POST['birthday']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $insuranceNum = trim($_POST['insuranceNum']);
    $diagnose = trim($_POST['diagnose']);
    $status = trim($_POST['status']);
    $description = trim($_POST['description']);
    $medication_bool = trim($_POST['medication_bool']);
    $medication = trim($_POST['medication']);
    $allergy_bool = trim($_POST['allergy_bool']);
    $allergy = trim($_POST['allergy']);
    
    # Initialize empty variables to check error
    $usernameError = $confirmPasswordErr = $phoneError = $insurancenumError = "";

    // Validate username
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $usernameError = "Username is not valid";
    } 
    $sql = "SELECT * FROM patient WHERE username = '$username'";
    $stmt = mysqli_query($conn,$sql);

    if (mysqli_num_rows($stmt) > 0){
        $usernameError = "Username is taken.";
    }
    // Validate password
    if ($password !== $confirmPass){
        $confirmPasswordErr = "Confirm Password does not match";
    }
    // Validate phone number
    $minDigits = $maxDigits = 10;
    if (!preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', trim($_POST["phone"]))){
        $phoneError = "Phone number is not valid";
    } 
    // Validate insurance
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["insuranceNum"]))){
        $insurancenumError = "Insurance Number is not valid.";
    }
    
    // If there is no error, then check if username exists in the database. If everything is good, then push data to database
    if ($usernameError == "" && $confirmPasswordErr == "" && $phoneError == "" && $insurancenumError == "") {
        // SELECT statement
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT); 
        // Store in the database 
        $sql = "INSERT INTO patient (username,password,firstName,lastName,email,gender,birthday,phone,address,insuranceNum,diagnose,status,description,medication_bool,medication,allergy_bool,allergy) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: register.php?error=sqlerror");
            exit();
        } else {
            // Bind variables
            mysqli_stmt_bind_param($stmt, "sssssssssssssssss", $username, $hashedpassword,$firstName,$lastName,$email,$gender,$birthday,$phone,$address,$insuranceNum,$diagnose,$status,$description,$medication_bool,$medication,$allergy_bool,$allergy);
            // Execute prepared statement   
            mysqli_stmt_execute($stmt);
            // Close statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            $_SESSION['success'] = "Successfully created an account. You can now log in.";
            header("Location: login.php?success=success");     
        }        
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
    <link rel="stylesheet" href="assets/fonts1/material-icon/css/material-design-iconic-font.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css1/style.css">
</head>
<body>
    <div class="main">
        <section class="signup">
            <!-- <img src="images/signup-bg.jpg" alt=""> -->
            <div class="container">
                <div class="signup-content">
                   <?php
                        if(!empty($usernameError)){
                            echo '<div style="color:red;" class="alert alert-danger">' . $usernameError . '</div>';
                            echo " ";	
                        }   
                        if(!empty($confirmPasswordErr)){
                            echo '<div style="color:red;" class="alert alert-danger">' . $confirmPasswordErr . '</div>';
                            echo " ";
                        }
 		                if(!empty($insurancenumError)){
                           echo '<div style="color:red;" class="alert alert-danger">' . $insurancenumError. '</div>';
                            echo " ";
                        }
		                if(!empty($phoneError)){
                            echo '<div style="color:red;" class="alert alert-danger">' . $phoneError. '</div>';
			                echo " ";
                        }   
                   ?>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"  class="signup-form">
                        
                        <h2 class="form-title">Sign up</h2>
 <!--Account info-->    <h1 class="form-title">Account information</h1>
                        <div class="form-group">
                            <input type="text" class="form-input <?php echo (!empty($usernameError)) ? 'is-invalid' : ''; ?>" name="username" placeholder="User Name" value="<?php if (isset($username)) echo $username;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input <?php echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-input" name="confirmPass" placeholder="Repeat your password">
                        </div> 
 <!--Personal info-->    <h1 class="form-title">Personal information</h1>
                        <div class="form-group">
                            <input type="text" class="form-input" name="firstName" placeholder="First Name" value="<?php if (isset($firstName)) echo $firstName;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="lastName" placeholder="Last Name" value="<?php if (isset($lastName)) echo $lastName;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-input" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email;?>" required>
                        </div>
                        <!--<div class="form-group">
                            <input type="text" class="form-input" name="gender" placeholder="Gender" required>
                        </div> -->
                        <div class="form-group">
                            <input type="text" onfocus="(this.type='date')" class="form-input" name="birthday" placeholder="Birthday" value="<?php if (isset($birthday)) echo $birthday;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="tele" class="form-input <?php echo (!empty($phoneError)) ? 'is-invalid' : ''; ?>" name="phone" placeholder="Phone Number" value="<?php if (isset($phone)) echo $phone;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input" name="address" placeholder="Address" value="<?php if (isset($address)) echo $address;?>" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-input <?php echo (!empty($insurancenumError)) ? 'is-invalid' : ''; ?>" name="insuranceNum" placeholder="Insurance Number" value="<?php if (isset($insuranceNum)) echo $insuranceNum;?>">
                        </div>
<!--Personal info-->    <h1 class="form-title">Medical Infomation</h1>
                        <h3 class="form-title">Gender</h3>  
                        <div class="form-group">
                            <input class="form-check-input" name="gender" value="female" type="radio" required>
                            <label class="form-check-label" for="inlineRadio1" >Female</label> 
                            <input class="form-check-input" name="gender" value="male" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >Male</label>
                            <input class="form-check-input" name="gender" value="other" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >Other</label>
                        </div>
                        <h3 class="form-title">Have you been diagnosed with COVID-19?</h3>  
                        <div class="form-group">
                            <input class="form-check-input" name="diagnose" value="yes" type="radio" required>
                            <label class="form-check-label" for="inlineRadio1" >Yes</label> 
                            <input class="form-check-input" name="diagnose" value="no" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >No</label>
                        </div>
                        <h3 class="form-title">Status</h3>  
                        <div class="form-group">
                            <input class="form-check-input" name="status" value="mild"  type="radio" required>
                            <label class="form-check-label" for="inlineRadio1" >Mid</label> 
                            <input class="form-check-input" name="status" value="severe" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >Severe</label>
                            <input class="form-check-input" name="status" value="critical" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >Critical</label>
                        </div>
                        <h3 class="form-title">Description</h3>  
                        <div class="form-group">
                            <textarea class="form-input" name="description" placeholder="Tell us how you're feeling?" cols="63" rows="10" value="<?php if (isset($description)) echo $description;?>" required> </textarea>
                            
                        </div>
                        <h3 class="form-title">Are you curently taking any medication?</h3>  
                        <div class="form-group">
                            <input class="form-check-input" name="medication_bool" value="yes" type="radio" required>
                            <label class="form-check-label" for="inlineRadio1" >Yes</label> 
                            <input class="form-check-input" name="medication_bool" value="no" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >No</label>
                        </div>
                        <h3 class="form-title">List any medication you're taking</h3>  
                        <div class="form-group">
                            <textarea name="medication" class="form-input" cols="63" rows="10" value="<?php if (isset($medication)) echo $medication;?>" > </textarea> 
                        </div>
                        <h3 class="form-title">Do you have any medication allergies?</h3>  
                        <div class="form-group">
                            <input class="form-check-input" name="allergy_bool" value="yes"  type="radio" required>
                            <label class="form-check-label" for="inlineRadio1" >Yes</label> 
                            <input class="form-check-input" name="allergy_bool" value="no" type="radio" >
                            <label class="form-check-label" for="inlineRadio1" >No</label>
                        </div>
                        <h3 class="form-title">List any allergies you may have</h3>  
                        <div class="form-group">
                            <textarea name="allergy" class="form-input" cols="63" rows="10" value="<?php if (isset($allergy)) echo $allergy;?>"> </textarea> 
                        </div>
                        <div class="form-group">
                            <input type="submit" class="form-submit" value="Submit">
                        </div>
                    </form>
                    <p class="loginhere">
                        Have already an account ? <a href="login.php" class="loginhere-link">Login here</a>
                    </p>
                </div>
            </div>
        </section>
    </div>
    <!-- JS -->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
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