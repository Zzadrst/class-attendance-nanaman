<?php
include_once("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bu_no = $_POST['bu_no'];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'img/'; 
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $updateQuery = "UPDATE students SET img = '$uploadFile' WHERE bu_no = '$bu_no'";
            if ($conn->query($updateQuery)) {
                echo "Photo uploaded successfully and database updated.";

                header("Location: view_students.php");
                exit();
            } else {
                echo "Error updating database: " . $conn->error;
                header("Location: view_students.php");
                exit();
            }
        } else {
            echo "Error uploading file.";
            header("Location: view_students.php");
            exit();
        }
    } else {
        echo "No file uploaded or an error occurred.";
        header("Location: view_students.php");
        exit();
    }
} else {
    echo "Invalid request method.";
    header("Location: view_students.php");
    exit();
}
?>
