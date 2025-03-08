<?php
include_once("include/connect.php");
session_start();

$user_id = $_SESSION['user_id'];
$sql = "SELECT DISTINCT date FROM deleted_records WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$deletedDates = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Define the cutoff date (30 days ago)
$cutoff_date = date('Y-m-d', strtotime('-30 days'));

// Prepare and execute the SQL statement to delete records older than 30 days
$sql_delete = "DELETE FROM deleted_records WHERE date < ?";
$stmt_delete = mysqli_prepare($conn, $sql_delete);
mysqli_stmt_bind_param($stmt_delete, "s", $cutoff_date);
mysqli_stmt_execute($stmt_delete);

// Check if any records were deleted
$deletion_message = (mysqli_affected_rows($conn) > 0) ? "Records older than 30 days have been deleted." : "No records older than 30 days found.";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="weblogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <?php include('include/link.php')?>
    <title>Recycle Bin</title>
</head>

<body>
    <?php include('include/nav.php') ?>
    <div class="container mt-5">
        <h3 class="text-center mb-5">Recycle Bin</h3>

        <?php if (empty($deletedDates)) : ?>
            <?php if ($deletion_message === "No records older than 30 days found.") : ?>
                <p class="text-center text-info"><?php echo $deletion_message; ?></p>
            <?php else: ?>
                <?php echo $deletion_message; ?>
            <?php endif; ?>
        <?php else : ?>
            <div class="accordion" id="accordionExample">
                <?php foreach ($deletedDates as $index => $deletedDate) : ?>
                    <?php
                    $date = $deletedDate['date'];
                    // Fetch all records for the current date
                    $sql = "SELECT * FROM deleted_records WHERE user_id = ? AND date = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "is", $user_id, $date);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $deletedRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
                    ?>

                    <div class="card">
                        <div class="card-header" id="heading<?php echo $index; ?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $index; ?>" aria-expanded="true" aria-controls="collapse<?php echo $index; ?>">
                                    <?php echo date("D, M d, Y", strtotime($date)); ?>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse<?php echo $index; ?>" class="collapse" aria-labelledby="heading<?php echo $index; ?>" data-bs-parent="#accordionExample">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">BU no</th>
                                            <th scope="col">Student Name</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($deletedRecords as $record) : ?>
                                            <tr>
                                                <td><?php echo $record['bu_no']; ?></td>
                                                <td><?php echo $record['student_name']; ?></td>
                                                <td><?php echo $record['status']; ?></td>
                                                <td><?php echo date("H:i:s", strtotime($record['date'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <a href="restore_records.php?delete_group=1&delete_date=<?php echo urlencode($date); ?>" class="btn btn-success">Restore All</a>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#deleteAllModal<?php echo $index; ?>">Delete All</button>

                                <!-- Modal -->
                                <div class="modal fade" id="deleteAllModal<?php echo $index; ?>" tabindex="-1" aria-labelledby="deleteAllModalLabel<?php echo $index; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteAllModalLabel<?php echo $index; ?>">Delete All Records Permanently?</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete all records permanently for this date? This action cannot be undone.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <a href="delete_all_records.php?delete_date=<?php echo urlencode($date); ?>" class="btn btn-danger">Delete All</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('include/modal.php'); ?>
</body>

</html>
