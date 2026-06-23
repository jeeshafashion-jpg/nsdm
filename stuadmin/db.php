<?php
// db.php — database connection

$host     = "localhost";
$username = "u477941187_nsdm_student";
$password = "Yamaha@123";
$dbname   = "u477941187_nsdm_student";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
