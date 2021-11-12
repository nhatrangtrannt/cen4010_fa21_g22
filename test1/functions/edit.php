<?php
session_start();
require_once "../config/config.php";


// Get variables to display first to know which user 
$id = $_GET['editid'];
$table = $_GET['table'];
$sql = "SELECT * FROM ".$table." WHERE id = $id";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$username = $row['username'];
$password = $row['password'];
$phone_num = $row['phone_num'];
//doctor assign rememeber to CHANGE
$doctor_assign = $row['first_name'];
    

# If submit button is pressed, then validate the input
if (isset($_POST['submit'])){
    # Obtain user's input
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPass = $_POST['confirmPass'];  
    $phone_num = $_POST['phone_num'];
    $doctor_assign = $_POST['doctor_assign'];

   
    # Initialize empty variables to check error
    $usernameError = $passwordError = $confirmPasswordErr = $phoneError = "";

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
    
    // Validate phone number 
    $minDigits = $maxDigits = 10;
    if(empty(trim($_POST["phone_num"]))){
        $phoneError = "Phone number is required";
    }elseif (!preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', trim($_POST["phone_num"]))){
        $phoneError = "Phone number is not valid";
    } 

    // If there is no error, then update data 
    if ($usernameError == "" && $passwordError == "" && $confirmPasswordErr == "" && $phoneError == "") {
       
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT); 
        $sql = "UPDATE ".$table." SET id = '$id', username = '$username', phone_num = '$phone_num', password = '$hashedpassword', first_name = '$doctor_assign' WHERE id = '$id'";
        $stmt = mysqli_query($conn,$sql);
        if($stmt){
            $_SESSION['success'] = "Successfully update ".$username; 
        }
        else{
            $_SESSION['error'] = "Fail to update".$id;
        }
        header("location: ../class/Admin.php");
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
        if(!empty($phoneError)){
            echo '<div class="alert alert-danger">' . $phoneError . '</div>';
        }        
        ?>
        <form action=""  method="POST">
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
                <label>Phone number</label>
                <input type="tel" value="<?php if (isset($phone_num)) echo $phone_num;?>" name="phone_num" class="form-control <?php echo (!empty($phoneError)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $phoneError; ?></span>      
            </div>
            <div class="form-group">
                <label>Doctor Assigned</label>
                <select name="doctor_assign" class="form-control"> 
                    <option value = "">Select Doctor</option>
                    <?php 
                        require_once "../config/config.php";

                        $sql = "SELECT * FROM doctor";
                        $stmt = mysqli_query($conn,$sql);
                        while ($row = mysqli_fetch_array($stmt)){
                            //1$first_name = $row['first_name'];
                            ?>
                            <option value="<?php echo $row["username"] ?>" <?php if ($row["first_name"] == $doctor_assign) echo "selected"; ?>> 
                            <?php echo $row["first_name"]; echo " "; echo $row["username"];?>
                            </option>
                            <?php
                            //1"<toption value='$first_name'>$first_name</option>";
                            //"<option value=" .$row['first_name']. ">$row['first_name']"</option>"; -->
                            //"<option value='$row["first_name"]'>$row["first_name"]</option>";
                        }
                            

                    ?>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name ="submit" value="Update">
                <input type="button" class="btn btn-light" value="Go back!" onclick="history.back()">
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