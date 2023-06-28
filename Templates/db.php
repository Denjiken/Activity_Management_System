<?php
$hostname = "localhost:3306";
$username = "root";
$password = "";
$dbname = "activity_db";

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $dbname);

// Check connection
if(!$conn)
{   
    die("Connection Failed" . mysqli_connect_errno());
}  
?>
