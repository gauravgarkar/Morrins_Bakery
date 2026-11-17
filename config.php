
<?php
$host = "localhost";
$database = "morrins_db";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");

