<?php
// Replace these variables with your actual database credentials
$hostname = "localhost";
$username = "root";
$password = "";
$database = "lab";

// Replace these variables with your actual database credentials
// $hostname = "sql307.infinityfree.com";
// $username = "if0_34596002";
// $password = "5V0xTnRLLQulAK";
// $database = "if0_34596002_lab";

// Create the MySQLi connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check the connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$variable = "Connected to database!!";

// Output JavaScript code with PHP values embedded
echo '<script>';
echo 'console.log(' . json_encode($variable) . ');';
echo '</script>';


?>

