<?php
session_start();
include_once("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blockName = $_POST['block_name'];
    $userId = $_POST['user_id']; // Fetch user_id from the form
    $school_yr = $_POST['school_year'];

    $sql = "INSERT INTO `block` (`block_name`, `user_id`, `school_yr`) VALUES ('$blockName', '$userId', '$school_yr')";
    if ($conn->query($sql) === TRUE) {
        header("Location: class.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close(); 
?>
