

<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] == 'patient' || $_SESSION["role"] == 'doctor'){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Admin's Page</title>
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
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
    <div class="container my-4">
        <h1 class="my-5 text-center">Hello <?php $username = $_SESSION['username']; echo "$username!"; ?> Welcome to our application.</h1>
        <!-- <button href='../functions/add.php' type="button" class="btn btn-primary d-flex justify-content-start">Add New User</button> -->    
        <a href='../admin/add.php' class='btn btn-primary'>Add New User</a> 
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
    </div>     

    <div class="container">   
        <div class="row gutters">
            <!-- User information tab -->
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="account-settings">
                            <div class="user-profile">
                                <div class="user-avatar">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="rounded-circle avatar-xl img-thumbnail" alt="Maxwell Admin">
                                </div>
                                <h5 class="text-center user-name"><?php echo $username?></h5>
                                <h6 class="text-center user-email">Captain@avengers.com</h6>
                                <a class="btn btn-block btn-danger" href="../logout.php">Logout</a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Doctor list and Patient list tab -->
            <div class="col-xl- col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#doctorList" data-toggle="tab">Doctors</a></li>
                            <li><a href="#patientList" data-toggle="tab">Patients</a></li>
                        </ul>
                        <div class="tab-content">
                            <!-- Doctor list tab -->
                            <div class="tab-pane active" id="doctorList">
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>First Name</th>
                                                <th>Phone number</th>
                                                <th>License number</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="items">
                                            <?php
                                                require_once "../config/config.php";
                                                $table = "doctor";
                                                $sql = "SELECT * FROM doctor ";
                                                $stmt = mysqli_query($conn,$sql);
                                                while ($row = mysqli_fetch_array($stmt)){
                                                    echo 
                                                    "<tr>
                                                        <td>" .$row['id']. "</td>
                                                        <td>" .$row['username']. "</td>
                                                        <td>" .$row['firstName']. "</td>
                                                        <td>" .$row['phone']. "</td>
                                                        <td>" .$row['licenseNum']. "</td>
                                                        <td>" .$row['email']. "</td>
                                                        <td>
                                                        <a href='../admin/edit.php?table=".$table."&editid=".$row['id']."' class='btn btn-success btn-sm'>Edit</a>
                                                        <a href='../admin/delete.php?table=".$table."&deleteid=".$row['id']."' class='btn btn-danger btn-sm'>Delete</a>
                                                        </td>
                                                    </tr>";
                                                } 
                                            ?>
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

                            <!--Patient List tab-->
                            <div class="tab-pane" id="patientList">
                                <hr>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>First Name</th>
                                                <th>Date of Birth</th>
                                                <th>Phone number </th>
                                                <th>Status </th>
                                                <th>Action </th>
                                            </tr>
                                        </thead>
                                        <tbody id="items">
                                            <?php
                                                require_once "../config/config.php";
                                                
                                                $table = "patient";
                                                $sql = "SELECT * FROM patient ";
                                                $stmt = mysqli_query($conn,$sql);
                                                while ($row = mysqli_fetch_array($stmt)){
                                                    echo 
                                                    "<tr>
                                                        <td>" .$row['id']. "</td>
                                                        <td>" .$row['username']. "</td>
                                                        <td>" .$row['firstName']. "</td>
                                                        <td>" .$row['birthday']. "</td>
                                                        <td>" .$row['phone']. "</td>
                                                        <td>" .$row['status']. "</td>
                                                        <td>
                                                        <a href='../admin/edit.php?table=".$table."&editid=".$row['id']."' class='btn btn-success btn-sm'>Edit</a>
                                                        <a href='../admin/delete.php?table=".$table."&deleteid=".$row['id']."' class='btn btn-danger btn-sm'>Delete</a>
                                                        </td>
                                                    </tr>";
                                                } 
                                            ?>
                                        </tbody>
                                    </table>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-4 col-md-offset-4 text-center">
                                            <ul class="pagination" id="myPager"></ul>
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
  