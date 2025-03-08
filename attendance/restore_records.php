<?php
include_once("include/connect.php");
session_start();

if (isset($_GET['delete_group']) && $_GET['delete_group'] == 1) {
    if (isset($_GET['delete_date'])) {
        $dateToRestore = mysqli_real_escape_string($conn, $_GET['delete_date']);
        
        $moveSql = "INSERT INTO archive (user_id, student_name, status, date, bu_no, block)
                    SELECT user_id, student_name, status, date, bu_no, block
                    FROM deleted_records
                    WHERE user_id = {$_SESSION['user_id']} AND date = '$dateToRestore'";
        if (mysqli_query($conn, $moveSql)) {
            $deleteSql = "DELETE FROM deleted_records WHERE user_id = {$_SESSION['user_id']} AND date = '$dateToRestore'";
            if (mysqli_query($conn, $deleteSql)) {
                header('Location: archive.php');
                exit();
            } else {
                echo "Error deleting records from deleted_records: " . mysqli_error($conn);
            }
        } else {
            echo "Error moving records to archive: " . mysqli_error($conn);
        }
    } else {
        echo "Error: delete_date parameter is missing.";
    }
} else {
    header('Location: archive.php');
    exit();
}
?>
