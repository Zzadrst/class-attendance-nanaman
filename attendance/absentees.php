<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="weblogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include('include/link.php')?>
    <link rel="stylesheet" href="css/user.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #007bff;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card p {
            margin: 0;
        }
        .card-header {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .card-header h2 {
            margin: 0;
            padding-bottom: 5px;
        }
        .card-body {
            padding: 10px 0;
        }
        .card-body p {
            margin-bottom: 10px;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-info {
            color: #0c5460;
            background-color: #d1ecf1;
            border-color: #bee5eb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
    <title>Class Attendance System</title>
</head>
<body>
<?php include('include/nav.php') ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>STUDENTS WITH 3 OR MORE ABSENCES</h2>
                </div>
                <div class="card-body">
                    <?php
                    include_once("include/connect.php");

                    $sql_absences = "SELECT `student_name`, `block`, COUNT(*) AS total_absences
                        FROM `archive`
                        WHERE `status` = 'absent'
                        GROUP BY `student_name`, `block`
                        HAVING COUNT(*) >= 3";

                    $result_absences = mysqli_query($conn, $sql_absences);

                    if (mysqli_num_rows($result_absences) > 0) {
                        while ($row = mysqli_fetch_assoc($result_absences)) {
                            $student_name = $row['student_name'];
                            $total_absences = $row['total_absences'];
                            $block = $row['block'];
                            echo "<p><strong>$student_name</strong> from <strong>$block</strong> has $total_absences absences.</p>"; 
                        }
                    } else {
                        echo "<p class='alert alert-info'>No students found with 3 or more absences.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h2>STUDENTS WITH 5 OR MORE LATES</h2>
                </div>
                <div class="card-body">
                    <?php
                    $sql_lates = "SELECT `student_name`, `block`, COUNT(*) AS total_lates
                                FROM `archive`
                                WHERE `status` = 'late'
                                GROUP BY `student_name`, `block`
                                HAVING COUNT(*) >= 5";

                    $result_lates = mysqli_query($conn, $sql_lates);

                    if (mysqli_num_rows($result_lates) > 0) {
                        while ($row = mysqli_fetch_assoc($result_lates)) {
                            $student_name = $row['student_name'];
                            $total_lates = $row['total_lates'];
                            $block = $row['block'];
                            echo "<p><strong>$student_name</strong> from <strong>$block</strong> has $total_lates lates.</p>"; 
                        }
                    } else {
                        echo "<p class='alert alert-info'>No students found with 5 or more lates.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('include/modal.php'); ?>
<?php include('include/footer.php'); ?>

</body>
</html>
