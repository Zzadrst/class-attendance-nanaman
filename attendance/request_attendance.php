<?php
include_once("include/connect.php");

if (isset($_GET['block_id'])) {
    $blockId = $_GET['block_id'];
    $user_id = $_GET['user_id'];
}

$sqlBlock = "SELECT `block_name` FROM `block` WHERE `block_id` = '$blockId'";
$resultBlock = $conn->query($sqlBlock);

if ($resultBlock->num_rows > 0) {
    $blockData = $resultBlock->fetch_assoc();
    $blockName = $blockData['block_name'];

    $sqlSelect = "SELECT `id`, `name`, `status`, `bu_no` FROM `students` WHERE `status` = 'active' AND `block` = '$blockId' AND `user_id` = '$user_id'";
    $resultSelect = $conn->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {

        while ($row = $resultSelect->fetch_assoc()) {
            $id = $row['id'];
            $studentName = $row['name'];
            $bu_no = $row['bu_no'];

            $sqlInsert = "INSERT INTO `attendance` (`id`, `student_name`, `status`, `date`, `bu_no`, `block`, `user_id`) VALUES ('$id', '$studentName', 'Pending', NOW(), '$bu_no', '$blockName', '$user_id')";

            if ($conn->query($sqlInsert) === TRUE) {
                echo "Record inserted successfully!<br>";
            } else {
                echo "Error inserting record: " . $conn->error . "<br>";
            }
        }

        $conn->close();

        header("Location: homepage.php");
        exit();

    } else {
        // No active students found for this block
        echo '<!doctype html>
        <html lang="en">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title>Bootstrap demo</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
          <style>
            .alert-short {
              padding: 0.75rem;
            }
          </style>
        </head>
        <body class="d-flex justify-content-center align-items-center" style="height: 100vh;">
          <div class="container">
            <div class="alert alert-warning alert-dismissible fade show text-center alert-short" role="alert">
              <strong>No data found!</strong> The student list is empty.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <hr>
              <a href="homepage.php" class="btn btn-info rounded-pill">Go back</a>
              <a href="class.php" class="btn btn-warning rounded-pill">Add students</a>
            </div>
          </div>
        </body>
        </html>';
    }
} else {
    // No block found with the provided block ID
    echo "Block not found.";
}
?>
