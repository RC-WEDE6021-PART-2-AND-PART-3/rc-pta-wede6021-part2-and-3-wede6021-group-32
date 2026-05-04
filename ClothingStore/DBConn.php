<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "clothingstore"; // Change this if your database name is different

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>