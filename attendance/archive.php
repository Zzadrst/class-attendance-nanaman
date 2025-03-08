<?php
include_once("include/connect.php");
session_start();

// Function to get status badge class
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Present':
            return 'bg-success';
        case 'Absent':
            return 'bg-danger';
        case 'Late':
            return 'bg-warning';
        case 'Excused':
            return 'bg-info';
        default:
            return 'bg-secondary';
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Loop through the posted status values and update the database
    foreach ($_POST['status'] as $attendanceId => $newStatus) {
        // Sanitize the input (to prevent SQL injection)
        $attendanceId = mysqli_real_escape_string($conn, $attendanceId);
        $newStatus = mysqli_real_escape_string($conn, $newStatus);

        // Update the status in the database
        $updateSql = "UPDATE archive SET status = '$newStatus' WHERE id = $attendanceId";
        mysqli_query($conn, $updateSql);
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch attendance data from the database for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT id, student_name as name, status, date FROM archive WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
$attendanceRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Organize attendance records by date
$groupedAttendance = array();
foreach ($attendanceRecords as $record_archive) {
    $groupedAttendance[$record_archive['date']][] = $record_archive;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="weblogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-BzrA6EJWqlmoJwKl67NfrEtv60UpP7KtKfeFgA2ZL+27HPrGG3soeJxYIyZe4CTYl3X71aKtf6r+3lQHUKrJbw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <?php include('include/link.php')?>
    <link rel="stylesheet" href="css/user.css">
    <title>Archive Attendance</title>
</head>

<body>
    <?php include('include/nav.php') ?>
    <div class="container mt-5 text-center">
        <h1>ARCHIVE ATTENDANCE</h1>
        <h3>Hello <?php echo $_SESSION["username"]; ?>!</h3>
        <p id="dateTime"></p>

        <!-- Trash icon with link to recycle_bin.php -->
        <div>
            <a href="recycle_bin.php" class="text-danger mb-5" style="text-decoration: none;">
                <i class="fas fa-trash-alt"></i> </br> Recycle Bin
            </a>
        </div>
    </div>

    <form action="" method="post">
    <div class="container mt-5">
    <?php foreach ($groupedAttendance as $date => $records): ?>
    <?php
    // Count the number of absent, present, late, and excused records for this group
    $countAbsent = $countPresent = $countLate = $countExcused = 0;

    foreach ($records as $record_archive) {
        switch ($record_archive['status']) {
            case 'Absent':
                $countAbsent++;
                break;
            case 'Present':
                $countPresent++;
                break;
            case 'Late':
                $countLate++;
                break;
            case 'Excused':
                $countExcused++;
                break;
        }
    }
    ?>

    <div class="accordion" id="accordion_<?php echo strtotime($date); ?>">
        <div class="accordion-item">
        <h2 class="accordion-header" id="heading_<?php echo strtotime($date); ?>">
    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_<?php echo strtotime($date); ?>" aria-expanded="true" aria-controls="collapse_<?php echo strtotime($date); ?>">
        <?php echo date("D, M d, Y g:i a", strtotime($date)); ?>
        <span class="badge rounded-pill bg-success ms-2">Present: <?php echo $countPresent; ?></span>
        <span class="badge rounded-pill bg-danger ms-2">Absent: <?php echo $countAbsent; ?></span>
        <span class="badge rounded-pill bg-warning ms-2">Late: <?php echo $countLate; ?></span>
        <span class="badge rounded-pill bg-info ms-2">Excused: <?php echo $countExcused; ?></span>
    </button>
</h2>

                    <div id="collapse_<?php echo strtotime($date); ?>" class="accordion-collapse collapse h-font" aria-labelledby="heading_<?php echo strtotime($date); ?>" data-bs-parent="#accordion_<?php echo strtotime($date); ?>">
                        <div class="accordion-body">
                            <!-- Add a form for deleting each group of records -->
                            <form action="" method="post">
                                <a href="delete_record.php?delete_group=1&delete_date=<?php echo $date; ?>" class="btn btn-outline-danger btn-sm mb-3 rounded-pill">
                                    Delete Record
                                </a>

                                <table class="table caption-top">
                                    <caption>BSIS - 2A</caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Names</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($records as $record_archive): ?>
                                            <tr>
                                                <td><?php echo $record_archive['id']; ?></td>
                                                <td><?php echo $record_archive['name']; ?></td>
                                                <td>
                                                    <div class="dropup-center dropup">
                                                        <select name="status[<?php echo $record_archive['id']; ?>]" class="form-select" onchange="this.form.submit()">
                                                            <option value="Present" <?php echo ($record_archive['status'] == 'Present') ? 'selected' : ''; ?>>Present</option>
                                                            <option value="Absent" <?php echo ($record_archive['status'] == 'Absent') ? 'selected' : ''; ?>>Absent</option>
                                                            <option value="Late" <?php echo ($record_archive['status'] == 'Late') ? 'selected' : ''; ?>>Late</option>
                                                            <option value="Excused" <?php echo ($record_archive['status'] == 'Excused') ? 'selected' : ''; ?>>Excused</option>
                                                        </select>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span id="status_<?php echo $record_archive['id']; ?>" class="badge rounded-pill <?php echo getStatusBadgeClass($record_archive['status']); ?>">
                                                        <?php echo $record_archive['status']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</form>

    <?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/updateDateTime.js"></script>
    <?php include('include/modal.php'); ?>

</body>

</html>
