<?php
$host = 'localhost';
$dbname = 'attendance'; 
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn){
    die();
}

?>
