<?php
// Initialize the session
session_start();
 
// Check if the user is logged in as a doctor, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] == 'patient'){
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
    <style>
        body{ 
            font: 14px sans-serif; 
            text-align: center; 
        }
    </style>
</head>
<body>
    <h1 class="my-5">Hello <?php $username = $_SESSION['username']; echo "$username!"; ?> Welcome to our application.</h1>
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
        $loginTable = $_SESSION['role'];
        $sql = "SELECT * FROM ".$loginTable." ";
        $stmt = mysqli_query($conn,$sql);
        while ($row = mysqli_fetch_array($stmt)){
            $id = $row['id'];
            $username = $row['username'];
            $password = $row['password'];
        ?>
        <tr> 
            <td><?php echo $id; ?> </td>
            <td><?php echo $username; ?> </td>
            <td><?php echo $password; ?> </td>
        </tr>
        <?php
        }
        ?>                  
	</tbody>
    </table>
    </div>
    <p><a href="../logout.php" class="btn btn-danger">Sign Out</a></p>
<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>