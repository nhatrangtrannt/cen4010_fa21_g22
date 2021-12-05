

<?php
// Initialize the session
session_start();
require_once "../config/config.php";

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] == 'admin' || $_SESSION["role"] == 'patient'){
    header("location: login.php");
    exit;
}

// Get previous information user entered 
$table = "doctor";
$username = $_SESSION["username"];
$sql = "SELECT * FROM ".$table." WHERE username = '$username'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
$id = $row['id'];
$username = $row['username'];
$password = $row['password'];
$firstName = $row['firstName'];
$lastName = $row['lastName'];
$email = $row['email'];
$gender = $row['gender'];
$birthday = $row['birthday'];
$phone = $row['phone'];
$address = $row['address'];
$licenseNum = $row['licenseNum'];
$insuranceAccept = $row['insuranceAccept'];


# If submit button (update profile) is pressed, then validate the input
if (isset($_POST['submit'])){
    
    # Obtain user's input
    $password = trim($_POST['password']);
    $confirmPass = trim($_POST['confirmPass']);    
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $gender = trim($_POST['gender']);
    $birthday = trim($_POST['birthday']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $licenseNum = trim($_POST['licenseNum']);
    $insuranceAccept = trim($_POST['insuranceAccept']);


   # Initialize empty variables to check error
    $confirmPasswordErr = $phoneError = $insuranceAcceptError = "";

    // Validate password
    if ($password !== $confirmPass){
        $confirmPasswordErr = "Confirm Password does not match";
        $_SESSION['error'] = "Confirm Password does not match";
    }
   // Validate phone number
   $minDigits = $maxDigits = 10;
   if (!preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', trim($_POST["phone"]))){
       $phoneError = "Phone number is not valid";
       $_SESSION['error'] = "Phone number is not valid";
   } 
   // Validate licensenum
   if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["licenseNum"]))){
       $insuranceAcceptError = "License Number is not valid.";
       $_SESSION['error'] = "License Number is not valid";
   }
 
     // If there is no error, then update data to database
    if ($confirmPasswordErr == "" && $phoneError == "" && $insuranceAcceptError == ""){
        // SELECT statement
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT); 
	// Keep the way it is if the password displayed now is hashed password (same in the database)
        if ($password == $row['password']){
            $hashedpassword = $password;
        }
        $sql = "UPDATE ".$table." SET id = '$id', password = '$hashedpassword', username = '$username', firstName = '$firstName',lastName = '$lastName', email = '$email', gender = '$gender', birthday = '$birthday', phone = '$phone', address = '$address', licenseNum = '$licenseNum', insuranceAccept = '$insuranceAccept' WHERE id = $id";

        //username = '$username', password = '$hashedpassword', firstName = '$firstName',lastName = '$lastName', email = '$email',gender = '$gender', birthday = '$birthday',phone = '$phone', address = '$address', insuranceNum = '$insuranceNum', diagnose = '$diagnose',status = '$status',description = '$description', symptom = '$symptom', medication_bool = '$medication_bool',medication = '$medication','allergy_bool = $allergy_bool',allergy = '$allergy')";
        $stmt = mysqli_query($conn,$sql);
        if($stmt){
            $_SESSION['success'] = "Successfully update your profile"; 
        }
        else{
            $_SESSION['error'] = "Fail to update your profile";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Doctor's Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/css1/doctorProfile.css">
        <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->

    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
 <style>
    body{
        margin: 10;
        padding-top: 40px;
        color: #002D62;
        background-image: linear-gradient(315deg, #63a4ff 0%, #83eaf1 74%);
        position: relative;
        height: 100%;
    }
</style>  
    
</head>
<body>
<div class="container">
        <h1 class="my-5 text-center">Hello <?php $username = $_SESSION['username']; echo "$username!"; ?> Welcome to our application.</h1>

        <!-- Success or Error Notification -->
        <?php
            if(isset($_SESSION['success'])){
                echo
                "<div class='alert alert-success text-center'>".$_SESSION['success']."</div>";
                unset($_SESSION['success']);
            }
            if(isset($_SESSION['error'])){
                echo
                "<div class='alert alert-danger text-center'>".$_SESSION['error']."</div>";
                unset($_SESSION['error']);
            }
        ?>
    <div class="row gutters">
        <!-- User's info tab -->
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="account-settings">
                        <div class="user-profile">                         
                            <div class="user-avatar">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Maxwell Admin">
                            </div>
                            <h5 class="user-name">Dr. <?php echo $firstName; echo " "; echo $lastName; ?></h5>
                            <h5 class="user-name"><?php echo $username;?></h5>
                            <h6 class="user-email"><?php echo $email; ?></h6>
                            <h7 class="user-name"><?php echo $licenseNum;?></h7>
                            <a class="btn btn-block btn-danger" href="../logout.php">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Patient, Messages, and Profile tab -->
        <div class="col-xl- col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card h-100">                
                <div class="col-sm-12">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#patientList" data-toggle="tab">Patients</a></li>
                        <li><a href="#messages" data-toggle="tab">Messages</a></li>
                        <li><a href="#settings" data-toggle="tab">Profile</a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Patient List tab -->
                        <div class="tab-pane active" id="patientList">
                            <hr>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th>Date of Birth</th>
                                            <th>Phone number </th>
                                            <th>Status </th>
                                            <th>Action </th>
                                        </tr>
                                    </thead>
                                    <tbody id="items">
                                        <tr>
                                        <?php
                                            require_once "../config/config.php";
                                            $doctor_username = $username;
                                            $table = "patient";
                                            $sql = "SELECT * FROM patient WHERE doctor_assign = '$doctor_username'";
                                        
                                            $stmt = mysqli_query($conn,$sql);
                                            while ($row = mysqli_fetch_array($stmt)){
                                                echo 
                                                "<tr>
                                                    <td>" .$row['id']. "</td>
                                                    <td>" .$row['username']. "</td>
                                                    <td>" .$row['firstName']. "</td>
                                                    <td>" .$row['lastName']. "</td>
                                                    <td>" .$row['birthday']. "</td>
                                                    <td>" .$row['phone']. "</td>
                                                    <td>" .$row['status']. "</td>
                                                    <td>
                                                    <a href='../doctor/doctor_patient.php?doctor_username=".$doctor_username."&patient_username=".$row['username']."' class='btn btn-success btn-sm'>Edit</a>
                                                    </td>
                                                </tr>";
                                            } 
                                        ?>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4 col-md-offset-4 text-center">
                                        <ul class="pagination" id="myPager"></ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>

                        <!--Messages from Patients-->
                        <div class="tab-pane" id="messages">
                            <hr>
                            <ul class="list-group">
                                <li class="list-group-item text-muted">Inbox</li>
                                <?php
                                    require_once "../config/config.php";
                                    $doctor_username = $username;
                                    $sql = "SELECT * FROM message WHERE doctor_username = '$doctor_username'";
                                
                                    $stmt = mysqli_query($conn,$sql);
                                    while ($row = mysqli_fetch_array($stmt)){
                                        echo 
                                        "<li class='list-group-item text-right'> <a class='pull-left'>" .$row['comment']. " </a>".$row['patient_username']." ".$row['date']."</td>";
                                    } 
                                ?>
                            </ul>
                        </div>

                        <!--Doctor's Profile to update-->
                        <div class="tab-pane" id="settings">
                            <hr>
                            <form class="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label><h4>First name</h4></label>
                                        <input type="text" class="form-control" name="firstName" value="<?php if (isset($firstName)) echo $firstName;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label><h4>Last name</h4></label>
                                        <input type="text" class="form-control" name="lastName" value="<?php if (isset($lastName)) echo $lastName;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label><h4>Birthday</h4></label>
                                        <input type="text" onfocus="(this.type='date')" class="form-control" name="birthday" value="<?php if (isset($birthday)) echo $birthday;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>License Number</h4></label>
                                        <input type="text" class="form-control" name="licenseNum" value="<?php if (isset($licenseNum)) echo $licenseNum;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Insurance Accepting</h4></label>
                                        <input type="text" class="form-control" name="insuranceAccept" placeholder="Humana, BrightHealth, FloridaBlue" value="<?php if (isset($insuranceAccept)) echo $insuranceAccept;?>">
                                    </div>
                                </div>                              
                                <div class="form-group">

                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Phone</h4></label>
                                        <input type="tele" class="form-control" name="phone" value="<?php if (isset($phone)) echo $phone;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Gender</h4></label>
                                            <br>
                                            <select class="form-control" name="gender">
                                                <option value="female" <?php if ($row["gender"] == "female") echo "selected"; ?>> Female</option>
                                                <option value="male" <?php if ($row["gender"] == "male") echo "selected"; ?>> Male</option>
                                                <option value="other" <?php if ($row["gender"] == "other") echo "selected"; ?>> Other</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Email</h4></label>
                                        <input type="email" class="form-control" name="email" value="<?php if (isset($email)) echo $email;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Address</h4></label>
                                        <input type="text" class="form-control" name="address" value="<?php if (isset($address)) echo $address;?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Password</h4></label>
                                            <input type="password" class="form-control <?php  echo (!empty($passwordError)) ? 'is-invalid' : ''; ?>" name="password" value = "<?php if (isset($password)) echo $password;?>">
                                            <span style="color:red;" class="invalid-feedback"><?php echo $passwordError; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label>
                                            <h4>Confirm Password</h4></label>
                                            <input type="password" class="form-control <?php  echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>" name="confirmPass" value = "<?php if (isset($password)) echo $password;?>">
                                            <span style="color:red;" class="invalid-feedback"><?php echo $confirmPasswordErr; ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <br>
                                        <input type="submit" class="btn btn-lg btn-success" name ="submit" value="Update"> 
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>   
            </div>
        </div>
    </div>
</div>
</body>
</html>