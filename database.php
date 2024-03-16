<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "password";
$dbName = "login_register";
$conn = mysqli_connect($hostName, $dbUser, $dbPassword, $dbName);
if (!$conn) {
    die("Something went wrong;");
}

?>