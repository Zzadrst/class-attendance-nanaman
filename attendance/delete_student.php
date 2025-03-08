<?php
include_once("include/connect.php");

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $delete_sql = "DELETE FROM students WHERE bu_no = '$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        header("Location: process_class.php");
        exit(); 
    } else {
        $delete_alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 80%;">';
        $delete_alert .= 'Error deleting student: ' . $conn->error;
        $delete_alert .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $delete_alert .= '</div>';
    }
}

if (isset($_POST['update_status'])) {
    $update_bu_no = $_POST['update_bu_no'];
    $new_status = $_POST['new_status'];

    $update_sql = "UPDATE students SET status = '$new_status' WHERE bu_no = '$update_bu_no'";
    if ($conn->query($update_sql) === TRUE) {
        header("Location: process_class.php");
        exit(); 
    } else {
        $update_alert = '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="font-size: 80%;">';
        $update_alert .= 'Error updating status: ' . $conn->error;
        $update_alert .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        $update_alert .= '</div>';
    }
}

$result = $conn->query("SELECT bu_no, name, status FROM students");
?>
