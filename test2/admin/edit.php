<?php
session_start();
require_once "../config/config.php";


// Get variables to display first to know which user so that output the existing information in the database 
$id = $_GET['editid'];
$table = $_GET['table'];
if ($table == "doctor"){
    $_SESSION['table'] = $table;
    $_SESSION["id"] = $id; 
    header("Location: ../admin/edit_doctor.php");

}
$sql = "SELECT * FROM ".$table." WHERE id = $id";
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
$symptom = $row['symptom'];
$medication_bool = $row['medication_bool'];
$medication = $row['medication'];
$allergy_bool = $row['allergy_bool'];
$allergy = $row['allergy'];
$doctor_assign = $row['doctor_assign'];

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
    $doctor_assign = trim($_POST['doctor_assign']);

    

   # Initialize empty variables to check error
    $confirmPasswordErr = $phoneError = $insurancenumError = "";

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
 

     // If there is no error, then push data to database
    if ($confirmPasswordErr == "" && $phoneError == "" && $insurancenumError == ""){
        // SELECT statement
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT); 
        $sql = "UPDATE ".$table." SET id = '$id', password = '$hashedpassword', username = '$username', firstName = '$firstName',lastName = '$lastName', email = '$email', gender = '$gender', birthday = '$birthday', phone = '$phone', address = '$address', insuranceNum = '$insuranceNum', diagnose = '$diagnose', status = '$status', description = '$description', medication_bool = '$medication_bool', medication = '$medication', allergy_bool = '$allergy_bool', allergy = '$allergy', doctor_assign = '$doctor_assign' WHERE id = $id";

        //username = '$username', password = '$hashedpassword', firstName = '$firstName',lastName = '$lastName', email = '$email',gender = '$gender', birthday = '$birthday',phone = '$phone', address = '$address', insuranceNum = '$insuranceNum', diagnose = '$diagnose',status = '$status',description = '$description', symptom = '$symptom', medication_bool = '$medication_bool',medication = '$medication','allergy_bool = $allergy_bool',allergy = '$allergy')";
        $stmt = mysqli_query($conn,$sql);
        if($stmt){
            $_SESSION['success'] = "Successfully update ".$username; 
        }
        else{
            $_SESSION['error'] = "Fail to update ".$username;
        }
        header("location: ../admin/admin.php");
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Profile</title>
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
        background: #3E8EDE;
        position: relative;
        height: 100%;
    }
    </style>     
</head>

<body>
<div class="container">
<div class="row gutters">
<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">
		<div class="account-settings">
			<div class="user-profile">
                
				<div class="user-avatar">
					<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Maxwell Admin">
				</div>
				<h5 class="user-name"><?php echo $username; ?></h5>
				<h6 class="user-email"><?php echo $email; ?></h6>
                <h4><?php echo $status; ?></h6>
			</div>
			<div class="about">
				<h5 style="font-size: 20px;">Doctor Assigned</h5>
						
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
                        <h5><?php echo $row['firstName'];?></h5>
                        <h5><?php echo $row['lastName'];?></h5>
                     

                        <?php
                    }
                        

                ?>
			</div>
           
		</div>
	</div>
</div>
</div>
<div class="col-xl- col-lg-9 col-md-12 col-sm-12 col-12">
<div class="card h-100">
    
         
        <div class="col-sm-12">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#home" data-toggle="tab">Personal Infomation</a></li>
                <li><a href="#messages" data-toggle="tab">Case Infomation</a></li>
                
                
                
<!-- SETTING TAB
                <li><a href="#settings" data-toggle="tab">Settings</a></li> -->
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="home">
                    <div class="table-responsive">
<div class="col-xl-9">
<div class="card h-100">
	<div class="card-body">
                <div class="row gutters">
                    
    <form action="" method="POST"  class="signup-form">

			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<h3 class="mt-3 mb-2 text-primary">Account information</h3>
			</div>
		
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Username</label>
					<input type="text" class="form-control" name="username"  value="<?php if (isset($username)) echo $username;?>" readonly>
                    
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
					<label>Confirm Password</label>
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
					<input type="text" class="form-control" name="firstName" placeholder="First Name" value="<?php if (isset($firstName)) echo $firstName;?>">
                    
				</div>
			</div>
	
        
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Last Name</label>
					<input type="text" class="form-control" name="lastName" placeholder="Last Name" value="<?php if (isset($lastName)) echo $lastName;?>">
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
					<label>Birthday</label>
					<input type="text" onfocus="(this.type='date')" class="form-control" name="birthday" placeholder="Birthday" value="<?php if (isset($birthday)) echo $birthday;?>">

				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Phone number</label>
					<input type="tele" class="form-control <?php echo (!empty($phoneError)) ? 'is-invalid' : ''; ?>" name="phone" placeholder="Phone Number" value="<?php if (isset($phone)) echo $phone;?>">
					<span style="color:red;" class="invalid-feedback"><?php echo $phoneError; ?></span>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Adress</label>
					<input type="text" class="form-control" name="address" placeholder="Address" value="<?php if (isset($address)) echo $address;?>" >
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Insurance number</label>
					<input type="text" class="form-control <?php echo (!empty($insurancenumError)) ? 'is-invalid' : ''; ?>" name="insuranceNum" placeholder="Insurance Number" value="<?php if (isset($insuranceNum)) echo $insuranceNum;?>">
					<span style="color:red;" class="invalid-feedback"><?php echo $insurancenumError; ?></span>
				</div>
			</div>
          <!--  <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="insurance">Frequency</label>
					<input type="text" class="form-control" id="frequency" placeholder="">
				</div>
			</div>-->
		</div>
        
        

		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
		
				<h3 class="mt-3 mb-2 text-primary">Medical record</h3>
			</div>
            
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					
					<label>Diagnosed</label>
                    <br>
					<select name="diagnose">
                        <option value="yes" <?php if ($row["diagnose"] == "yes") echo "selected";?>> Yes</option>
                        <option value="no" <?php if ($row["diagnose"] == "no") echo "selected";?>>No</option>
                    </select>

				</div>
                <div class="form-group">
					<label>Gender</label>
                    <br>
					  <select name="gender">
                        <option value="female" <?php if ($row["gender"] == "female") echo "selected"; ?>> Female</option>
                        <option value="male" <?php if ($row["gender"] == "male") echo "selected"; ?>> Male</option>
                        <option value="other" <?php if ($row["gender"] == "other") echo "selected"; ?>> Other</option>
                      </select>
				</div>
			</div>

         
            
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label for="status">Status</label>
                    <br>
					  <select name="status">
                        <option value="mild" <?php if ($row["status"] == "mild") echo "selected";?>>Mild </option>
                        <option value="severe" <?php if ($row["status"] == "severe") echo "selected";?>>Severe</option>
                        <option value="critical" <?php if ($row["status"] == "critical") echo "selected"; ?>>Critical</option>
                      </select>
				</div>
                <div class="form-group">
					<label>Doctor Assigned</label>
                    <br>
                    <select name="doctor_assign"> 
                    <option value = "">Select Doctor</option>
                    <?php 
                        require_once "../config/config.php";

                        $sql = "SELECT * FROM doctor";
                        $stmt = mysqli_query($conn,$sql);
                        while ($row = mysqli_fetch_array($stmt)){
                            //1$first_name = $row['first_name'];
                            ?>
                            <option value="<?php echo $row["username"] ?>" <?php if ($row["username"] == $doctor_assign) echo "selected"; ?>> 
                            <?php echo $row["firstName"]; echo " "; echo $row["lastName"];?>
                            </option>
                            <?php
                        }
                            

                    ?>
                </select>
				</div>
			</div>
            
			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Description</label>
					<textarea name = "description" cols="55" rows="4" placeholder="Tell us how you are feeling here..."><?php if (isset($description)) echo $description;?></textarea>
				</div>
			</div>
            
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
		
			</div>
            
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
				<div class="form-group">
					<label>Are you curently taking any medication?</label>
                    <br>
					  <select name="medication_bool">
                        <option value="yes" <?php if ($row["medication_bool"] == "yes") echo "selected";?>> Yes</option>
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
                    <br>
					  <select name="allergy_bool">
                        <option value="yes" <?php if ($row["allergy_bool"] == "yes") echo "selected";?>> Yes</option>
                        <option value="no" <?php if ($row["allergy_bool"] == "no") echo "selected";?>>No</option>
                      </select>
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
						<div class="form-group">
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
                        <hr>
                        <div class="row">
                            <div class="col-md-4 col-md-offset-4 text-center">
                                <ul class="pagination" id="myPager"></ul>
                            </div>
                        </div>
                    </div>
                    <!--/table-resp-->

                    <hr>

                </div>
                <!--/tab-pane-->
                <div class="tab-pane" id="messages">

        
            <div class="tab-content">
              
                    <div class="table-responsive">

	<div class="card-body">
        <div class="row gutters">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<h3 class="mt-3 mb-2 text-primary">Case information</h3>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="Street">Patient Name</label>
						<input type="patientName" class="form-control" id="patientName" placeholder="">
					</div>
				</div>
			
			
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="doctorName">Doctor Name</label>
						<input type="text" class="form-control" id="doctorName" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="caseStatus">Case ID</label>
						<input type="" class="form-control" id="caseId" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="caseId">Case Status</label>
						<input type="text" class="form-control" id="caseId" placeholder="">
					</div>
				</div>
            
		</div>
        

            
            <div class="row gutters">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<h3 class="mb-2 text-primary">Prescription Form</h3>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="firstName">Date</label>
						<input type="date" class="form-control" id="firstName" placeholder="">
					</div>
				</div>   
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<h4 class="mb-2 text-primary">Patient Infomation</h4>
				</div>
    
                        
        
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="firstName">First Name</label>
						<input type="text" class="form-control" id="firstName" placeholder="">
					</div>
				</div>
		
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="birthday">Last Name</label>
						<input type="data" class="form-control" id="birthday" placeholder="">
					</div>
				</div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="birthday">Age</label>
						<input type="data" class="form-control" id="birthday" placeholder="">
					</div>
				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="birthday">Gender</label>
						<input type="data" class="form-control" id="birthday" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="eMail">Email</label>
						<input type="email" class="form-control" id="email" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="phone">Phone</label>
						<input type="text" class="form-control" id="phone" placeholder="">
					</div>
				</div>
	
            	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="Street">Street</label>
						<input type="name" class="form-control" id="street" placeholder="">
					</div>
				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="ciTy">City</label>
						<input type="name" class="form-control" id="cit" placeholder="">
					</div>
				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="sTate">State</label>
						<input type="text" class="form-control" id="state" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="zIp">Zip Code</label>
						<input type="text" class="form-control" id="zip" placeholder="">
					</div>
				</div>
            	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
					<label for="insurance">Insurance Number</label>
						<input type="text" class="form-control" id="insurance" placeholder="">
					</div>
				</div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<h4 class="mb-2 text-primary">Pharmacy Infomation</h4>
				</div>

				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="firstName">Pharmacy Name</label>
						<input type="text" class="form-control" id="firstName" placeholder="">
					</div>
				</div>
	
 
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="eMail">Email</label>
						<input type="email" class="form-control" id="email" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="phone">Phone</label>
						<input type="text" class="form-control" id="phone" placeholder="">
					</div>
				</div>
	
            	<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="Street">Street</label>
						<input type="name" class="form-control" id="street" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="ciTy">City</label>
						<input type="name" class="form-control" id="cit" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="sTate">State</label>
						<input type="text" class="form-control" id="state" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="zIp">Zip Code</label>
						<input type="text" class="form-control" id="zip" placeholder="">
					</div>
				</div>
            		
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
					<h4 class="mb-2 text-primary">Prescription Detail</h4>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance"> Medication 1 </label>
						<input type="text" class="form-control" id="medicationName" placeholder="">
					</div>
				</div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Quantity</label>
						<input type="text" class="form-control" id="quantity" placeholder="">
					</div>
				</div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Route</label>
						<input type="text" class="form-control" id="route" placeholder="">
					</div>
				</div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Frequency</label>
						<input type="text" class="form-control" id="frequency" placeholder="">
					</div>
				</div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Strength</label>
						<input type="text" class="form-control" id="strength" placeholder="">
					</div>
				</div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Note</label>
						<input type="text" class="form-control" id="insurance" placeholder="">
					</div>
				</div>
            
          
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<br>
					
					<div class="form-group">
						<label for="insurance">Medication 2</label>
						<input type="text" class="form-control" id="medicationName" placeholder="">
					</div>
				</div>
				
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<br>
					<div class="form-group">
						<label for="insurance">Quantity</label>
						<input type="text" class="form-control" id="quantity" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Route</label>
						<input type="text" class="form-control" id="route" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Frequency</label>
						<input type="text" class="form-control" id="frequency" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Strength</label>
						<input type="text" class="form-control" id="strength" placeholder="">
					</div>
				</div>
				
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Note</label>
						<input type="text" class="form-control" id="insurance" placeholder="">
					</div>
				</div>
							
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<br>
					<div class="form-group">
						<label for="insurance">Medication 3</label>
						<input type="text" class="form-control" id="medicationName" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<br>
					<div class="form-group">
						<label for="insurance">Quantity</label>
						<input type="text" class="form-control" id="quantity" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Route</label>
						<input type="text" class="form-control" id="route" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Frequency</label>
						<input type="text" class="form-control" id="frequency" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Strength</label>
						<input type="text" class="form-control" id="strength" placeholder="">
					</div>
				</div>
				<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
					<div class="form-group">
						<label for="insurance">Note</label>
						<input type="text" class="form-control" id="insurance" placeholder="">
					</div>
				</div>           
			</div>
        
           
            
		</div>
		<div class="row gutters">
			<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
				<div class="text-right">
					<button type="button" id="submit" name="submit" class="btn btn-secondary">Cancel</button>
					<button type="button" id="submit" name="submit" class="btn btn-primary">Update</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</div>
                 
                    </div>
                    <!--/table-resp-->

                    <hr>

                </div>

            </div>

                </div>
                <!--/tab-pane
                <div class="tab-pane" id="settings">

                    <hr>
                    <form class="form" action="##" method="post" id="registrationForm">
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="first_name">
                                    <h4>First name</h4></label>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first name" title="enter your first name if any.">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="last_name">
                                    <h4>Last name</h4></label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last name" title="enter your last name if any.">
                            </div>
                        </div>
                        
                         <div class="form-group">
                            <div class="col-xs-6">
                                <label for="license">
                                    <h4>License Number</h4></label>
                                <input type="text" class="form-control" name="license" id="license" placeholder="license number" title="enter your license number.">
                            </div>
                        </div>
                     <div class="form-group">
                            <div class="col-xs-6">
                                <label for="license">
                                    <h4>Insurance Accepting</h4></label>
                                <input type="text" class="form-control" name="insuranceAccepting" id="insuranceAccepting" placeholder="Humana, Bluecross, Oscar" title="enter your insurance accepting.">
                            </div>
                        </div>
                        
                        
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="phone">
                                    <h4>Phone</h4></label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="enter phone" title="enter your phone number if any.">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-6">
                                <label for="mobile">
                                    <h4>Mobile</h4></label>
                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="enter mobile number" title="enter your mobile number if any.">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="email">
                                    <h4>Email</h4></label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email.">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="email">
                                    <h4>Location</h4></label>
                                <input type="email" class="form-control" id="location" placeholder="somewhere" title="enter a location">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="password">
                                    <h4>Password</h4></label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password.">
                            </div>
                        </div>
                        <div class="form-group">

                            <div class="col-xs-6">
                                <label for="password2">
                                    <h4>Verify</h4></label>
                                <input type="password" class="form-control" name="password2" id="password2" placeholder="password2" title="enter your password2.">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <div class="col-xs-12">
                                <br>
                                <button class="btn btn-lg btn-success" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                <button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                            </div>
                        </div>
                    </form>
                </div>-->

            </div>
            <!--/tab-pane-->
        </div>   
                    </body>
                    </html>

    
  