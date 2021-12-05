
   
<?php
// Initialize the session
session_start();
require_once "../config/config.php";
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] == 'admin' || $_SESSION["role"] == 'doctor'){
    header("location: login.php");
    exit;
}


// Get variables to output the existing information in the database 
$patient_username = $_SESSION['username'];
$table = "patient";
$sql = "SELECT * FROM ".$table." WHERE username = '$patient_username'";
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
$insuranceNum = $row['insuranceNum'];
$diagnose = $row['diagnose'];
$status = $row['status'];
$description = $row['description'];
$medication_bool = $row['medication_bool'];
$medication = $row['medication'];
$allergy_bool = $row['allergy_bool'];
$allergy = $row['allergy'];
$doctor_assign = $row['doctor_assign'];

# If comment button is pressed,
if (isset($_POST['comment'])){
    $comment = trim($_POST['cmt']);
    $comment = str_replace("'","\'",$comment);
    if (isset($comment)){
        $sql = "INSERT INTO message (doctor_username, patient_username,comment) VALUES ('$doctor_assign', '$patient_username','$comment')";
        $stmt = mysqli_query($conn,$sql);
        if($stmt){
            $_SESSION['success'] = "Successfully send your message"; 
        }
        else{
            $_SESSION['error'] = "Fail to send your message";
        }
    }
}

# If submit button is pressed, then validate the input
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
    // Validate insurance
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["insuranceNum"]))){
       $insurancenumError = "Insurance Number is not valid.";
       $_SESSION['error'] = "Insurance Number is not valid.";
    }

     // If there is no error, then update data to database
    if ($confirmPasswordErr == "" && $phoneError == "" && $insurancenumError == ""){
        // SELECT statement
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT); 
        // Keep the way it is if the password displayed now is hashed password (same in the database)
        if ($password == $row['password']){
            $hashedpassword = $password;
        }
        $sql = "UPDATE ".$table." SET id = '$id', password = '$hashedpassword', username = '$username', firstName = '$firstName',lastName = '$lastName', email = '$email', gender = '$gender', birthday = '$birthday', phone = '$phone', address = '$address', insuranceNum = '$insuranceNum', diagnose = '$diagnose', status = '$status', description = '$description', medication_bool = '$medication_bool', medication = '$medication', allergy_bool = '$allergy_bool', allergy = '$allergy' WHERE id = $id";

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
    <title>Patient's Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/css1/doctorProfile.css">
        
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>

    <style>
        body {
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
    <div class="row gutters">   
        <!-- User's information tab (Patient and Doctor's Info) -->
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="card-body">
                    <div class="account-settings">
                        <div class="user-profile">
                            <div class="user-avatar">
                                <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Maxwell Admin">
                            </div>
                            <h5 class="user-name"><?php echo $firstName; echo " "; echo $lastName;?></h5>
                            <h5 class="user-email"><?php echo $username; ?></h5>
                            <h6 class="user-email"><?php echo $email; ?></h6>
                            <h4><?php echo $status; ?></h4>
                        </div>
                        <div class="about">
                            <h5>Doctor Assigned</h5>
                        </div>
                        <div class="user-profile">
                            <div class="user-avatar">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="Maxwell Admin">
                            </div>
                            <h5 class="user-name"><?php echo $doctor_assign; ?></h5>
                            <?php 
                                $sql = "SELECT * FROM doctor WHERE username = '$doctor_assign'";
                                $result = mysqli_query($conn,$sql);
                                while ($row = mysqli_fetch_array($result))
                                {
                                    ?>
                                    <h5><?php echo "Dr. "; echo $row['firstName']; echo " "; echo $row['lastName']; ?></h5>
                                    <h5><?php echo $row['email'];?></h5>
                                    <h5><?php echo $row['phone'];?></h5>
                                    <h5><?php echo $row['licenseNum'];?></h5>
                                    <h5><?php echo $row['insuranceAccept'];?></h5>        
                                    <?php
                                }
                            ?>
                        </div>
                        <a class="btn btn-block btn-danger" href="../logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Patient's Info, Comment, Prescriptions from doctor, and Previous message sent tab -->
        <div class="col-xl- col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#home" data-toggle="tab">Patient's Infomation</a></li>
                        <li><a href="#comment" data-toggle="tab">Comment</a></li>
                        <li><a href="#prescription" data-toggle="tab">Prescriptions</a></li>
                        <li><a href="#message" data-toggle="tab">Previous Message Sent</a></li>
                    </ul>
                    
                    <div class="tab-content">
                         <!-- Patient's Information tab -->
                        <div class="tab-pane active" id="home">
                            <div class="table-responsive">
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-body">
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
                                                <form class="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <h3 class="mt-3 mb-2 text-primary">Account information</h3>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label>User Name</label>
                                                            <input type="text" class="form-control" name="username"  value="<?php if (isset($username)) echo $username;?>"readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label>Password</label>
                                                            <input type="password" class="form-control <?php  echo (!empty($passwordError)) ? 'is-invalid' : ''; ?>" name="password" value = "<?php if (isset($password)) echo $password;?>">
                                                            <span style="color:red;" class="invalid-feedback"><?php echo $passwordError; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label><h4>Confirm Password</h4></label>
                                                            <input type="password" class="form-control <?php  echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>" name="confirmPass" value = "<?php if (isset($password)) echo $password;?>">
                                                            <span style="color:red;" class="invalid-feedback"><?php echo $confirmPasswordErr; ?></span>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="row gutters">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <h3 class="mb-2 text-primary">Personal Details</h3>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>First Name</label>
                                                        <input type="text" class="form-control" name="firstName" value="<?php if (isset($firstName)) echo $firstName;?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Last Name</label>
                                                        <input type="text" class="form-control" name="lastName" value="<?php if (isset($lastName)) echo $lastName;?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <br>
                                                        <select class="form-control" name="gender">
                                                            <option value="female" <?php if ($row["gender"] == "female") echo "selected"; ?>> Female</option>
                                                            <option value="male" <?php if ($row["gender"] == "male") echo "selected"; ?>> Male</option>
                                                            <option value="other" <?php if ($row["gender"] == "other") echo "selected"; ?>> Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email;?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control" name="phone" value="<?php if (isset($phone)) echo $phone;?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Adress</label>
                                                        <input type="text" class="form-control" name="address" placeholder="Address" value="<?php if (isset($address)) echo $address;?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Insurance number</label>
                                                        <input type="text" class="form-control" name="insuranceNum" placeholder="Insurance Number" value="<?php if (isset($insuranceNum)) echo $insuranceNum;?>">
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Birthday</label>
                                                        <input type="text" onfocus="(this.type='date')" class="form-control" name="birthday" value="<?php if (isset($birthday)) echo $birthday;?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gutters">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <h3 class="mt-3 mb-2 text-primary">Medical record</h3>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Diagnosed</label>
                                                        <br>
                                                        <select class="form-control" name="diagnose">
                                                            <option value="yes" <?php if ($row["diagnose"] == "yes") echo "selected";?>> Yes</option>
                                                            <option value="no" <?php if ($row["diagnose"] == "no") echo "selected";?>>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <br>
                                                        <select class="form-control" name="status">
                                                            <option value="mild" <?php if ($row["status"] == "mild") echo "selected";?>>Mild </option>
                                                            <option value="severe" <?php if ($row["status"] == "severe") echo "selected";?>>Severe</option>
                                                            <option value="critical" <?php if ($row["status"] == "critical") echo "selected"; ?>>Critical</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Decription</label>
                                                        <textarea name = "description" cols="55" rows="4" placeholder="Tell us how you are feeling here..."><?php if (isset($description)) echo $description;?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Are you curently taking any medication?</label>
                                                        <br>
                                                        <select class="form-control" name="medication_bool">
																<option value="yes" <?php if ($row["medication_bool"] == "yes") echo "selected";?>>Yes</option>
																<option value="no" <?php if ($row["medication_bool"] == "no") echo "selected";?>>No</option>
														</select>
                                                    </div>
                                                    <div class="form-group">
                                                            <label>List any medication you are taking</label>
                                                            <textarea name = "medication" cols="55" rows="4" placeholder="List any medication you are taking here..."><?php if (isset($medication)) echo $medication;?> </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                            <label>Do you have any medication allergies?</label>
                                                            <select class = "form-control" name="allergy_bool">
                                                                <option value="yes" <?php if ($row["allergy_bool"] == "yes") echo "selected";?>> Yes</option>
                                                                <option value="no" <?php if ($row["allergy_bool"] == "no") echo "selected";?>>No</option>
                                                            </select>
                                                            <br>
                                                        </div>
                                                    <div class="form-group">
                                                        <label>List any allergies you may have</label>
                                                        <textarea name = "allergy" cols="55" rows="4" placeholder="List any allergies you may have here..."><?php if (isset($allergy)) echo $allergy;?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gutters">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="text-right">
                                                    <input type="submit" class="btn btn-primary" name ="submit" value="Update">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Comment tab -->
                        <div class="tab-pane" id="comment">
                            <div class="table-responsive">
                                <div class="card-body">
                                    <form class="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                    <div class="row gutters">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <h3 class="mt-3 mb-2 text-primary">Please describe any concerns to your doctor</h3>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Comment</label>
                                                    <textarea name = "cmt" cols="55" rows="4" placeholder="Leave a note for your doctor." required></textarea>
                                                </div>
                                            </div>  
                                    </div>
                                </div>
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="text-right">
                                                <input type="submit" class="btn btn-primary" name ="comment" value="Comment">
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                            </div>
                        </div> 

                        <!-- Presciption tab -->
                        <div class="tab-pane" id="prescription">
                            <div class="table-responsive">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <form action="" method="POST"  class="signup-form">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Medication</th>
                                                        <th>Strength</th>
                                                        <th>Quantity</th>
                                                        <th>Usage</th>
                                                        <th>Comment</th>
                                                        <th>Pharmacy</th>
                                                        <th>Address</th>
                                                        <th>Phone number</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="items">
                                                    <?php
                                                        require_once "../config/config.php";
                                                        
                                        
                                                        $sql = "SELECT * FROM prescription WHERE patient_username = '$patient_username'";
                                                        $stmt = mysqli_query($conn,$sql);
                                                        while ($row = mysqli_fetch_array($stmt)){
                                                            echo 
                                                            "<tr>
                                                                <td>" .$row['id']. "</td>
                                                                <td>" .$row['medication']. "</td>
                                                                <td>" .$row['strength']. "</td>
                                                                <td>" .$row['quantity']. "</td>
                                                                <td>" .$row['note']. "</td>
                                                                <td>" .$row['comment']. "</td>
                                                                <td>" .$row['pharmacy']. "</td>
                                                                <td>" .$row['pharm_address']. "</td>
                                                                <td>" .$row['pharm_phone']. "</td>
                                                                <td>" .$row['date']. "</td>
                                                            </tr>";
                                                        } 
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  Previous Messsage Sent tab -->
                        <div class="tab-pane" id="message">
                            <div class="table-responsive">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <div class="table-responsive">
                                            <ul class="list-group">
                                                <li class="list-group-item text-muted">Inbox</li>
                                                <?php
                                                    require_once "../config/config.php";
                                                    $sql = "SELECT * FROM message WHERE patient_username = '$username'";
                                                    $stmt = mysqli_query($conn,$sql);
                                                    while ($row = mysqli_fetch_array($stmt)){
                                                        echo 
                                                        "<li class='list-group-item text-right'> <a class='pull-left'>" .$row['comment']. "  </a>".$row['date']."</td>";
                                                    } 
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
    </div>
</div>     
</body>
</html>  