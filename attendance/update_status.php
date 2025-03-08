<?php
include_once("include/connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['status'] as $attendanceId => $newStatus) {
        $attendanceId = mysqli_real_escape_string($conn, $attendanceId);
        $newStatus = mysqli_real_escape_string($conn, $newStatus);

        $updateSql = "UPDATE attendance SET status = '$newStatus' WHERE id = $attendanceId";
        mysqli_query($conn, $updateSql);
    }

    header('Location: homepage.php');
    exit();
}
?>