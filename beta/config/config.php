<?php

$server = "localhost";
$dbuser = "cen4010_fa21_g22";
$pass = "team22nnh";
$db = "cen4010_fa21_g22";
// Database connection (server,username,password,database)
$conn = new mysqli($server,$dbuser,$pass,$db);
// Check connection
if($conn === false){
    die("COULD NOT CONNECT. ".$conn->connect_error);
}
?>