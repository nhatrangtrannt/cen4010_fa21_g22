<?php
// Initialize the session
session_start();
 
// Check if the user is logged in as a admin, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] == 'patient' || $_SESSION["role"] == 'doctor'){
    header("location: index.html");
    exit;
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    
    <style>
        body{ 
            font: 14px sans-serif; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h1 class="my-5">Hello <?php $username = $_SESSION['username']; echo "$username!"; ?> Welcome to our application.</h1>
        <!-- <button href='../functions/add.php' type="button" class="btn btn-primary d-flex justify-content-start">Add New User</button> -->
        <a href='../functions/add.php' class='btn btn-primary'>Add New User</a>

    </div>
    
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
	
   
        <!-- Tab Navigation -->
        <div class="container my-4">
            <ul class="nav nav-pills d-flex justify-content-center" id="myTab">
                <li class="nav-item">
                    <a href="#doctorList" class="nav-link active">Doctor</a>
                </li>
                <li class="nav-item">
                    <a href="#patientList" class="nav-link">Patient</a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <!-- Doctor List Tab -->
            <div class="tab-pane fade show active" id="doctorList">
                <h2>Doctor Listing</h2>
                <p>Below is the list of doctor</p>
                <div class="container">
                    <table class="table table-bordered" id="doctorList">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                        <td>" .$row['password']. "</td>
                                        <td>
                                        <a href='../functions/edit.php?table=".$table."&editid=".$row['id']."' class='btn btn-success btn-sm'>Edit</a>
                                        <a href='../functions/delete.php?table=".$table."&deleteid=".$row['id']."' class='btn btn-danger btn-sm'>Delete</a>
                                        </td>
                                    </tr>";
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
                        
            <!-- Patient List Tab -->                   
            <div class="tab-pane fade" id="patientList">
                <h2>Patient Listing</h2>
                <p>Below is the list of patients</p>
                <div class="container">
                    <table class="table table-bordered" id="patientList">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Password</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                        <td>" .$row['password']. "</td>
                                        <td>
                                        <a href='../functions/edit.php?table=".$table."&editid=".$row['id']."' class='btn btn-success btn-sm' data-toggle='modal'><span class='glyphicon glyphicon-edit'></span> Edit</a>
                                        <a onClick='return confirm('Are you sure you want to delete?')' href='../functions/delete.php?table=".$table."&deleteid=".$row['id']."' class='btn btn-danger btn-sm' data-toggle='modal'><span class='glyphicon glyphicon-trash'></span> Delete</a>
                                        </td>
                                    </tr>";
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
                            
    

    
    <p>
        <a href="../logout.php" class="btn btn-danger">Sign Out</a>
    </p>

  


<script type="text/javascript" >
 $(document).ready(function(){
    $("#myTab a").click(function(e){
        e.preventDefault();
        $(this).tab("show");
    });
});
</script>


     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->

</body>
</html>