<?php
session_start();

include_once("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["block_id"]) && isset($_GET["user_id"])) {
    $block_id = $_GET["block_id"];
    $user_id = $_GET["user_id"];

    // Prepare the SQL statement with user_id condition
    $sql = "SELECT * FROM `block` WHERE `block_id` = $block_id AND `user_id` = $user_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Encode the block name to display "ñ" correctly
        $block_name = mb_convert_encoding($row["block_name"], 'UTF-8', 'ISO-8859-1');

    } else {
        echo "No data found for the selected block.";
        exit(); 
    }

} else {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="weblogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAS - CLASS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <?php include('include/link.php')?>

</head>
<body>
<?php include('include/nav.php') ?>

<div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center"><?php echo $row["block_name"]; ?></h2>
</div>

<h2 class="h-font text-center">Add Student</h2>

<!-- Button trigger modal -->
<div class="container">
    <div class="row">
        <div class="text-center d-flex justify-content-center">
            <button type="button" class="btn btn-warning rounded-pill mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                ADD STUDENT
            </button>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="add_student.php" method="POST">
                    <!-- Personal Information -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="bu_no" class="form-label">BU No:</label>
                        <input type="text" class="form-control" name="bu_no" placeholder="Enter BU No" required>
                    </div>
                    <!-- Hidden input for user_id -->
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
                    
                    <!-- Hidden input for block -->
                    <input type="hidden" name="block_id" value="<?php echo $row["block_id"]; ?>">
            
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="reg_id" class="form-label">Reg ID:</label>
                            <input type="text" class="form-control" name="reg_id" placeholder="Enter Reg ID">
                        </div>
                        <div class="col-md-6">
                            <label for="birthday" class="form-label">Birthday:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="birthday" placeholder="Enter Birthday">
                                <span class="input-group-text">
                                    <span class="badge bg-secondary">YYYY-MM-DD</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="contact_no" class="form-label">Contact Number:</label>
                        <input type="text" class="form-control" name="contact_no" placeholder="Enter Contact no">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="email_no1" class="form-label">Email no1:</label>
                            <input type="text" class="form-control" name="email_no1" placeholder="Enter Email 1">
                        </div>
                        <div class="col-md-6">
                            <label for="email_no2" class="form-label">Email no2:</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="email_no2" placeholder="Enter Email 2" required>
                                <span class="input-group-text">
                                    <span class="badge bg-secondary">BU Email</span>
                                </span>
                            </div>
                        </div>

                    </div>
                    <!-- Gender -->
                    <div class="mb-3">
                        <label class="form-label">Gender:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                    </div>
                    <!-- Year Level -->
                    <div class="mb-3">
                        <label class="form-label">Year Level:</label>
                        <select class="form-select" name="year_level" required>
                            <option value="" selected disabled>Select Year Level</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-info rounded-pill">
                        ADD STUDENT
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->

<div class="container">
    <div class="row">
        <div class="text-center d-flex justify-content-center">
            <button type="button" class="btn btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#importModal">
                IMPORT EXCEL STUDENT LIST
            </button>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="alert alert-info" role="alert">
                        1 Should be BLOCK : IS 204-BSIS2A <br>
                        2 ID NUMBER, NAME, Reg ID, Status, Birthdate, Gender, Year Level, Contact #, Email Add 1, Email Add 2 format only
                    </div>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="block_id" value="<?php echo $block_id; ?>">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION["user_id"]; ?>">
                        <label for="fileInput" class="form-label">Select Files (CSV, XLSX, XLS):</label>
                        <input type="file" class="form-control" id="fileInput" name="fileInput" accept=".csv, .xlsx, .xls">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-info rounded-pill">Import</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <h3 class="text-center mt-5">Student List</h3>
            <?php
            // Fetch total number of students for the current block and user
            $query = "SELECT SUM(total_students) as total_students FROM (SELECT COUNT(*) as total_students FROM students WHERE block = '$block_id') AS subquery";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_students = $row['total_students'];
            } else {
                $total_students = 0;
            }
            ?>
            <p class="text-center">Total students: <?php echo $total_students; ?></p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                        <th>BU No</th>
                        <th>Delete</th>
                        <th>View</th>                    
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch student data based on block and user_id
                    $user_id = $_SESSION["user_id"];
                    $query = "SELECT `name`, `status`, `bu_no`, `block`, `user_id` FROM `students` WHERE `block` = '$block_id' AND `user_id` = $user_id";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($student = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$student['name']}</td>";
                            echo "<td>
                                <form method='POST' action='update_student_status.php'>
                                    <input type='hidden' name='update_bu_no' value='{$student['bu_no']}'>";
                            echo "<select name='new_status' class='form-select form-select-sm' onchange='this.form.submit()'>
                                        <option value='active' " . ($student['status'] == 'active' ? 'selected' : '') . " style='color: green;'>Active</option>
                                        <option value='inactive' " . ($student['status'] == 'inactive' ? 'selected' : '') . " style='color: red;'>Inactive</option>
                                    </select>
                                    <input type='hidden' name='update_status' value='1'>
                                </form>
                                </td>";
                            echo "<td>{$student['bu_no']}</td>";
                            echo "<td><a href='delete_student.php?delete_id={$student['bu_no']}' class='btn btn-outline-danger btn-sm rounded-circle'><i class='fas fa-trash'></i></a></td>";
                            echo "<td><a href='view_students.php?bu_no={$student['bu_no']}' class='btn btn-outline-info btn-sm rounded-circle'><i class='fas fa-eye'></i></a></td>";                            
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4' class='text-center'>No students found.</td></tr>"; 
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require('include/footer.php'); ?>
    
</body>
</html>
