<?php
session_start();
require_once "../config/config.php";


// Get variables to output the existing information in the database 
$doctor_username = $_GET['doctor_username'];
$patient_username = $_GET['patient_username'];
$table = "patient";
$sql = "SELECT * FROM ".$table." WHERE username = '$patient_username'";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);
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

# If submit button is pressed, then validate the input
if (isset($_POST['submit'])){
    # Obtain user's input
    $diagnose = trim($_POST['diagnose']);
    $status = trim($_POST['status']);

    $sql = "UPDATE ".$table." SET username = '$patient_username', diagnose = '$diagnose', status = '$status' WHERE username = '$patient_username'";

    $stmt = mysqli_query($conn,$sql);
    if($stmt){
        $_SESSION['success'] = "Successfully update ".$patient_username; 
    }
    else{
        $_SESSION['error'] = "Fail to update ".$patient_username;
    }
    header("location: ../doctor/doctor.php");
    
}
// If Presribe button is pressed
if (isset($_POST['prescribe'])){
    
    # Obtain user's input
    $date = trim($_POST['date']);
    $pharmacy = trim($_POST['pharmacy']);
    $pharm_address = trim($_POST['pharm_address']);
    $pharm_phone= trim($_POST['pharm_phone']);
    $meds = trim($_POST['meds']);
    $quantity = trim($_POST['quantity']);
    $strength = trim($_POST['strength']);
    $note = trim($_POST['note']);
    $comment = trim($_POST['comment']);
    
    // Validate empty input
    if (empty($date) || empty($pharmacy) || empty($pharm_address) || empty($pharm_phone) || empty($meds) || empty($quantity) || empty($strength) || empty($note)){
        $emptyError = "Fail to add a prescription. One of the field is msising.";
        $_SESSION['error'] = $emptyError;
    }
    // Validate quantity (max 2 digits for meds)
    $minDigits = 1;
    $maxDigits = 2;
    if (!preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', trim($_POST["quantity"]))){
        $quantityError = "Quantity is not valid";
        $_SESSION['error'] = $quantityError;
    } 
    // Validate phone number
    $minDigitsphone = $maxDigitsphone = 10;
    if (!preg_match('/^[0-9]{'.$minDigitsphone.','.$maxDigitsphone.'}\z/', trim($_POST["pharm_phone"]))){
        $phoneError = "Phone number is not valid";
        $_SESSION['error'] = $phoneError;
    } 
    // If there is no error, then insert to the database
    if ($emptyError == "" && $quantityError == "" && $phoneError == ""){
        $sql = "INSERT INTO prescription (doctor_username, patient_username,comment,medication,pharmacy,pharm_address,pharm_phone,quantity,strength,note,date) VALUES ('$doctor_username','$patient_username','$comment','$meds','$pharmacy','$pharm_address','$pharm_phone','$quantity','$strength','$note','$date')";
        $stmt = mysqli_query($conn,$sql);
        if($stmt){
            $_SESSION['success'] = "Successfully add a prescription ".$patient_username; 
        }
        else{
            $_SESSION['error'] = "Fail to add a prescription ".$patient_username;
        }
    } 
    // Back to main doctor page
    header("location: ../doctor/doctor.php");
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Edit Patient's Info</title>
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
    <div class="row gutters">
        <!--User's info tab (Doctor and Patient) -->
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
                        <a class="btn btn-block btn-secondary" onclick="history.back()">Go back</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Paitient tab: Info, Presribe meds to patient, Previous Prescriptions, and Messages from patient) -->
        <div class="col-xl- col-lg-9 col-md-12 col-sm-12 col-12">
            <div class="card h-100">
                <div class="col-sm-12">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#home" data-toggle="tab">Patient's Infomation</a></li>
                        <li><a href="#prescribe" data-toggle="tab">Prescribe</a></li>
                        <li><a href="#prescription" data-toggle="tab">Prescriptions</a></li>
                        <li><a href="#message" data-toggle="tab">Patient's Message</a></li>
                    </ul>        
                    <div class="tab-content">
                         <!-- Patient's Information tab -->
                        <div class="tab-pane active" id="home">
                            <div class="table-responsive">
                                <div class="col-12">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <div class="row gutters">
                                                <form class="signup-form" action="" method="POST">
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <h3 class="mt-3 mb-2 text-primary">Account information</h3>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                        <div class="form-group">
                                                            <label>User Name</label>
                                                            <input type="text" class="form-control" name="username"  value="<?php if (isset($username)) echo $username;?>" readonly>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="row gutters">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <h3 class="mb-2 text-primary">Personal Details</h3>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Name</label>
                                                        <input type="text" class="form-control" name="name" value="<?php if (isset($firstName)) echo $firstName; echo " "; echo $lastName;?>"readonly>
                                                    </div>
                                                </div>

                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Gender</label>
                                                        <input type="text" class="form-control" name="gender" value="<?php if (isset($gender)) echo $gender;?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email;?>"readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control" name="phone" value="<?php if (isset($phone)) echo $phone;?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Adress</label>
                                                        <input type="text" class="form-control" name="address" placeholder="Address" value="<?php if (isset($address)) echo $address;?>"readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Insurance number</label>
                                                        <input type="text" class="form-control" name="insuranceNum" placeholder="Insurance Number" value="<?php if (isset($insuranceNum)) echo $insuranceNum;?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Birthday</label>
                                                        <input type="text" onfocus="(this.type='date')" class="form-control" name="birthday" value="<?php if (isset($birthday)) echo $birthday;?>" readonly>

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
                                                        <select class= "form-control" name="diagnose">
                                                            <option value="yes" <?php if ($row["diagnose"] == "yes") echo "selected";?>> Yes</option>
                                                            <option value="no" <?php if ($row["diagnose"] == "no") echo "selected";?>>No</option>
                                                        </select>
                                                    </div>
                                                </div>      
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label for="status">Status</label>
                                                        <br>
                                                        <select class= "form-control" name="status">
                                                            <option value="mild" <?php if ($row["status"] == "mild") echo "selected";?>>Mild </option>
                                                            <option value="severe" <?php if ($row["status"] == "severe") echo "selected";?>>Severe</option>
                                                            <option value="critical" <?php if ($row["status"] == "critical") echo "selected"; ?>>Critical</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Decription</label>
                                                        <textarea readonly name = "description" cols="55" rows="4" placeholder="Tell us how you are feeling here..."><?php if (isset($description)) echo $description;?></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                        <label>Are you curently taking any medication?</label>
                                                        <input type="text" class="form-control" name="prevmedication_bool" value="<?php if (isset($medication_bool)) echo $medication_bool;?>" readonly>
                                                        <br>                               
                                                    </div>
                                                    <div class="form-group">
                                                            <label>List any medication you are taking</label>
                                                            <textarea readonly name = "medication" cols="55" rows="4" placeholder="List any medication you are taking here..."><?php if (isset($medication)) echo $medication;?> </textarea>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                </div>
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                    <div class="form-group">
                                                            <label>Do you have any medication allergies?</label>
                                                            <input type="text" class="form-control" name="prevallergy_bool" value="<?php if (isset($allergy_bool)) echo $allergy_bool;?>" readonly>
                                                            <br>
                                                        </div>
                                                    <div class="form-group">
                                                        <label>List any allergies you may have</label>
                                                        <textarea readonly name = "allergy" cols="55" rows="4" placeholder="List any allergies you may have here..."><?php if (isset($allergy)) echo $allergy;?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gutters">
                                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                    <div class="text-right">
                                                    <input type="submit" class="btn btn-primary" name ="submit" value="Update">
                                                    <input type="button" class="btn btn-secondary" value="Go back!" onclick="history.back()">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Prescribe tab -->
                        <div class="tab-pane" id="prescribe">
                            <div class="table-responsive">
                                <div class="card-body">
                                    <form class="signup-form" action="" method="POST">
                                    <div class="row gutters">
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                <h3 class="mt-3 mb-2 text-primary">Case information</h3>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label for="Street">Patient UserName</label>
                                                    <input type="text" class="form-control" name="patient_username" value="<?php if (isset($patient_username)) echo $patient_username;?>"readonly>                                   
                                    
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label for="doctorName">Doctor Name</label>
                                                    <input type="text" class="form-control" ame="doctor_username" value="<?php if (isset($doctor_username)) echo $doctor_username;?>"readonly>                                   
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                                <div class="form-group">
                                                    <label>Case Status</label>
                                                    <input type="text" class="form-control" name="status" value="<?php if (isset($status)) echo $status;?>"readonly>
                                                </div>
                                            </div>
                                    </div>               
                                    <div class="row gutters">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h3 class="mb-2 text-primary">Prescription Form</h3>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="date" class="form-control" name="date" placeholder="">
                                            </div>
                                        </div>         
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h4 class="mb-2 text-primary">Patient Infomation</h4>
                                        </div>
                                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="name" value="<?php if (isset($firstName)) echo $firstName; echo " "; echo $lastName;?>"readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Gender</label>
                                                <input type="text" class="form-control" name="gender" value="<?php if (isset($gender)) echo $gender;?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="email" placeholder="Email" value="<?php if (isset($email)) echo $email;?>"readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" class="form-control" name="phone" value="<?php if (isset($phone)) echo $phone;?>" readonly>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Adress</label>
                                                <input type="text" class="form-control" name="address" placeholder="Address" value="<?php if (isset($address)) echo $address;?>"readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Insurance number</label>
                                                <input type="text" class="form-control" name="insuranceNum" placeholder="Insurance Number" value="<?php if (isset($insuranceNum)) echo $insuranceNum;?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Birthday</label>
                                                <input type="text" onfocus="(this.type='date')" class="form-control" name="birthday" value="<?php if (isset($birthday)) echo $birthday;?>" readonly>

                                            </div>
                                        </div>
                                        <!--Pharmacy detail -->
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h4 class="mb-2 text-primary">Pharmacy Infomation</h4>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Pharmacy Name</label>
                                                <input type="text" class="form-control" name="pharmacy">
                                            </div>
                                        </div>

                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" class="form-control" name="pharm_address">
                                            </div>
                                        </div>
                        
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" class="form-control" name="pharm_phone" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h4 class="mb-2 text-primary">Prescription Detail</h4>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label> Medication</label>
                                                <input type="text" class="form-control" name="meds">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="text" class="form-control" name="quantity">
                                            </div>
                                        </div> 
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Strength</label>
                                                <input type="text" class="form-control" name="strength">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <input type="text" class="form-control" name="note">
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <h4 class="mb-2 text-primary">Doctor's Comment</h4>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Comment</label>
                                                <textarea name = "comment" cols="55" rows="4" placeholder="Leave a note for your patient."></textarea>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                                <div class="row gutters">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="text-right">
                                            <input type="submit" class="btn btn-primary" name ="prescribe" value="Prescribe">
                                            <input type="button" class="btn btn-secondary" value="Go back!" onclick="history.back()">
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
                        <!-- Messsage tab -->
                        <div class="tab-pane" id="message">
                            <div class="table-responsive">
                                <div class="card-body">
                                    <div class="row gutters">
                                        <div class="table-responsive">
                                            <ul class="list-group">
                                                <li class="list-group-item text-muted">Inbox</li>
                                                <?php
                                                    require_once "../config/config.php";
                                                    
                                                    $sql = "SELECT * FROM message WHERE patient_username = '$patient_username'";
                                                
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

    
  