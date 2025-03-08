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

// Get the current year
$currentYear = date('Y');
$schoolYearStart = $currentYear - 1;
$schoolYearEnd = $currentYear;
$schoolYear = $schoolYearStart . ' - ' . $schoolYearEnd;

// Fetch attendance data from the database
$sql = "SELECT id, student_name as name, status, date FROM attendance";
$result = mysqli_query($conn, $sql);
$attendanceRecords = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/user.css">
    <?php include('include/link.php') ?>
    <link rel="icon" type="image/png" href="weblogo.png">
    <title>Class Attendance System</title>
</head>

<body>
    <?php include('include/nav.php') ?>

    <!-- Modal -->
    <div class="modal fade" id="requestForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="requestForm" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Select Block</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $sql = "SELECT `block_id`, `block_name` FROM `block` WHERE `user_id` = '{$_SESSION['user_id']}'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $_SESSION['block_name'] = $row["block_name"];
                            $user_id = $_SESSION['user_id']; // Set user_id session here

                            // Display a button for each row
                            echo '<div class="col-md-4 mb-3 mt-5">
                                    <form method="get" action="request_attendance.php">
                                        <input type="hidden" name="block_id" value="' . $row['block_id'] . '">
                                        <input type="hidden" name="user_id" value="' . $user_id . '"> 
                                        <button type="submit" class="btn btn-warning d-block">' . $row['block_name'] . '</button>  
                                    </form>
                                </div>';
                        }
                    } else {
                        echo '<div class="col-12 text-center">No classes created</div>';
                        // Add a button to navigate to class.php
                        echo '<div class="col-12 text-center mt-3">
                                <a href="class.php" class="btn btn-info">Create a Class</a>
                              </div>';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <h3 class="text-center h-font fw-bold" style="color: #00a0dc;">Hello <?php echo $_SESSION["username"]; ?>!</h3>
        <p id="dateTime" class="text-center h-font mb-5"></p>

        <!-- Display the school year -->
        <p class="text-center h-font mb-3">School Year: <?php echo $schoolYear; ?> </p>

        <!-- Count section -->
        <?php
        $presentCount = 0;
        $absentCount = 0;
        $lateCount = 0;
        $excusedCount = 0;

        // Arrays to store names for each status
        $presentNames = [];
        $absentNames = [];
        $lateNames = [];
        $excusedNames = [];

        foreach ($attendanceRecords as $record) {
            switch ($record['status']) {
                case 'Present':
                    $presentCount++;
                    $presentNames[] = $record['name'];
                    break;
                case 'Absent':
                    $absentCount++;
                    $absentNames[] = $record['name'];
                    break;
                case 'Late':
                    $lateCount++;
                    $lateNames[] = $record['name'];
                    break;
                case 'Excused':
                    $excusedCount++;
                    $excusedNames[] = $record['name'];
                    break;
            }
        }
        ?>

        <div class="d-flex justify-content-center mb-3">
            <button type="button" class="btn btn-outline-success rounded-pill mx-2" data-bs-toggle="modal" data-bs-target="#presentModal">
                Present: <?php echo $presentCount; ?>
            </button>

            <button type="button" class="btn btn-outline-danger rounded-pill mx-2" data-bs-toggle="modal" data-bs-target="#absentModal">
                Absent: <?php echo $absentCount; ?>
            </button>

            <button type="button" class="btn btn-outline-warning rounded-pill mx-2" data-bs-toggle="modal" data-bs-target="#lateModal">
                Late: <?php echo $lateCount; ?>
            </button>

            <button type="button" class="btn btn-outline-info rounded-pill mx-2" data-bs-toggle="modal" data-bs-target="#excusedModal">
                Excused: <?php echo $excusedCount; ?>
            </button>
        </div>

        <!-- Modal templates for status -->
        <?php
        function createStatusModal($id, $title, $names) {
            echo '<div class="modal fade" id="' . $id . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="' . $id . 'Label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">' . $title . ' Students</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" style="background: linear-gradient(135deg, #aedcf0, #fde3b0);">
                                ' . implode('', array_map(fn($name) => "<p>$name</p>", $names)) . '
                            </div>
                        </div>
                    </div>
                </div>';
        }

        createStatusModal('presentModal', 'Present', $presentNames);
        createStatusModal('absentModal', 'Absent', $absentNames);
        createStatusModal('lateModal', 'Late', $lateNames);
        createStatusModal('excusedModal', 'Excused', $excusedNames);
        ?>

        <h2 class="mt-5 h-font mb-3">Class Attendance System</h2>
        <?php if (empty($attendanceRecords)): ?>
            <div class="container mt-3 text-center">
                <button type="button" class="btn btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#requestForm">
                    Select Class
                </button>
            </div>
        <?php else: ?>
            <?php
            // Sort the $attendanceRecords array based on the 'name' column in ascending order
            usort($attendanceRecords, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            ?>

            <?php
            // Query to fetch block name
            $query = "SELECT DISTINCT `block` FROM `attendance`";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $blockRow = mysqli_fetch_assoc($result);
                $blockName = $blockRow['block'];
            } else {
                $blockName = "Unknown Block";
            }

            mysqli_free_result($result);
            ?>
            <form action="submit_attendance.php" method="post">
                <table class="table caption-top">
                    <caption><?php echo $blockName; ?></caption>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Names</th>
                            <th scope="col">Action</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceRecords as $index => $record): ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo $record['name']; ?></td>
                                <td>
                                    <div class="dropup-center dropup">
                                        <select name="status[<?php echo $record['id']; ?>]" class="form-select" onchange="this.form.submit()">
                                            <option value="Present" <?php echo ($record['status'] == 'Present') ? 'selected' : ''; ?>>Present</option>
                                            <option value="Absent" <?php echo ($record['status'] == 'Absent') ? 'selected' : ''; ?>>Absent</option>
                                            <option value="Late" <?php echo ($record['status'] == 'Late') ? 'selected' : ''; ?>>Late</option>
                                            <option value="Excused" <?php echo ($record['status'] == 'Excused') ? 'selected' : ''; ?>>Excused</option>
                                        </select>
                                    </div>
                                </td>
                                <td><span class="badge <?php echo getStatusBadgeClass($record['status']); ?>"><?php echo $record['status']; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        <?php endif; ?>
    </div>

    <?php include('include/footer.php'); ?>

    <script src="js/user.js"></script>
</body>

</html>
