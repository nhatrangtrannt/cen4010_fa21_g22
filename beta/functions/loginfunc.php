<?php

// Check role to look up in the database
function login($role){

    if ($role == "admin"){
        $loginTable = "admin";
    } elseif ($role == "doctor"){
        $loginTable = "doctor";
    } elseif ($role == "patient"){
        $loginTable = "patient";
    }
    return $loginTable;
}

?>