<?php 
session_start();
require_once "../config/config.php";


if (isset($_GET['deleteid'])){
    
    $id = $_GET['deleteid'];
    $table = $_GET['table'];

    // Get username to display the user
    $sql = "SELECT * FROM ".$table." WHERE id = $id";
    $result = mysqli_query($conn,$sql);
    $row = mysqli_fetch_assoc($result);
    $username = $row['username'];

    // Delete the user
    $sql = "DELETE FROM ".$table." WHERE id = $id";
    $stmt = mysqli_query($conn,$sql);
    if($stmt){
		//echo "DELETED SUCCESS";
        $_SESSION['success'] = "Successfully delete ".$username;
    }
	else{
		$_SESSION['error'] = "Fail to delete".$username;
	}
    
}
else{
    $_SESSION['error'] = "Please select member you want to delete";
}
header("location: ../admin/admin.php");

?>
