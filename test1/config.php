<?php
$server = "localhost";
$dbuser = "cen4010_fa21_g22";
$pass = "team22nnh";
$db = "cen4010_fa21_g22";

// Database connection (server,username,password,database)
//$conn = new mysqli($server,$dbuser,$pass,$db,$port);// Check connection
$conn = mysqli_connect($server,$dbuser,$pass,$db);

if($conn === false){
    //die("COULD NOT CONNECT. ".$conn->connect_error);
	die("Connection failed: ".mysqli_connect_error());
}
?>