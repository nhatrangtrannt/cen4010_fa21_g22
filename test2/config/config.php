<?php

$server = "localhost";
$dbuser = "ntran2020";
$pass = "nhatran123";
$db = "ntran2020";
// Database connection (server,username,password,database)
$conn = new mysqli($server,$dbuser,$pass,$db);
// Check connection
if($conn === false){
    die("COULD NOT CONNECT. ".$conn->connect_error);
}
?>