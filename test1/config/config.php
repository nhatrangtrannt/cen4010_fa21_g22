<?php

$server = "localhost";
$dbuser = "root";
$pass = "root";
$db = "test";
// Database connection (server,username,password,database)
$conn = new mysqli($server,$dbuser,$pass,$db);
// Check connection
if($mysqli === false){
    die("COULD NOT CONNECT. ".$conn->connect_error);
}
?>