<?php
$host = "localhost";
$username = "root"; // change if needed
$password = "";     // change if needed
$dbname = "project_manager";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
