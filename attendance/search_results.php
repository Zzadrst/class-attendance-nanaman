<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
</head>
<body>
<?php include('include/nav.php') ?>

<?php
include_once("include/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["searchkey"]) && !empty($_GET["searchkey"])) {
        $searchKey = mysqli_real_escape_string($conn, $_GET["searchkey"]); 

        $query = "SELECT `id`, `name`, `status`, `bu_no`, `img` 
                  FROM `students`
                  WHERE 
                  `name` LIKE '%$searchKey%' OR
                  `bu_no` LIKE '%$searchKey%'";

        $result = mysqli_query($conn, $query);

        $dateQuery = "SELECT `id`, `user_id`, `student_name`, `status`, `date`, `bu_no` 
        FROM `archive`
        WHERE
        DATE_FORMAT(`date`, '%b %e') = '$searchKey'";


$dateResult = mysqli_query($conn, $dateQuery);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bu_no = $row['bu_no'];

                $countQuery = "SELECT 
                                COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent_count,
                                COUNT(CASE WHEN status = 'Present' THEN 1 END) as present_count,
                                COUNT(CASE WHEN status = 'Late' THEN 1 END) as late_count,
                                COUNT(CASE WHEN status = 'Excused' THEN 1 END) as excused_count
                               FROM archive WHERE bu_no = '$bu_no'";
                $countResult = $conn->query($countQuery);

                if ($countResult->num_rows > 0) {
                    $countRow = $countResult->fetch_assoc();

                    $absentCount = $countRow['absent_count'];
                    $presentCount = $countRow['present_count'];
                    $lateCount = $countRow['late_count'];
                    $excusedCount = $countRow['excused_count'];
                }

                echo "<div class='row mb-3 mt-1 ml-5'>";
                echo "<div class='col-md-8 mt-5'>";
                echo "<div class='card shadow'>";
                echo "<div class='card card-sm'>"; 
                echo "<div class='card-body' style=\"background: url('img/card.jpg') no-repeat center center fixed; background-size: cover;\">";
echo "<div class='row'>"; 

echo "<div class='col-6'>";
echo "<div class='student-image-container'>";
echo "<img src='{$row['img']}' alt='Student Photo' class='img-fluid mb-3 rounded-circle' style='width: 100px; height: 100px;'>";
echo "</div>"; 
echo "</div>"; 

// Left column for student details
echo "<div class='col-6'>";
echo "<h2 class='card-title'>{$row['name']}</h2>";
echo "<p class='card-text'>BU No: {$row['bu_no']}</p>";
echo "<p class='card-text badge rounded-pill " . ($row['status'] == 'active' ? 'bg-success' : 'bg-danger') . "'>{$row['status']}</p>";
echo "<h3>Attendance Counts:</h3>";
echo "<p>Absent: $absentCount</p>";
echo "<p>Present: $presentCount</p>";
echo "<p>Late: $lateCount</p>";
echo "<p>Excused: $excusedCount</p>";

echo "</div>"; 


echo "</div>"; 
echo "</div>";

                echo "</div>"; 
                
                
                echo "</div>";
                echo "</div>";
                echo "<div class='col-md-4'>";
                echo "<canvas class='pieChart' width='100' height='100'></canvas>";
                echo "<script>
                        var ctx = document.querySelector('.pieChart').getContext('2d');
                        var pieChart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: ['Absent', 'Present', 'Late', 'Excused'],
                                datasets: [{
                                    data: [$absentCount, $presentCount, $lateCount, $excusedCount],
                                    backgroundColor: [
                                        'rgba(255, 99, 132, 0.7)', // Red
                                        'rgba(144, 238, 144, 0.7)', // Green
                                        'rgba(255, 205, 86, 0.7)', // Yellow
                                        'rgba(173, 216, 230, 0.7)', // Light Blue
                                    ],
                                    borderColor: [
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(144, 238, 144, 1)',
                                        'rgba(255, 205, 86, 1)',
                                        'rgba(173, 216, 230, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                            }
                        });
                    </script>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<div class='alert alert-info' role='alert'>
            No matching results found for the search key: $searchKey</div>";
        }

        if ($dateResult && mysqli_num_rows($dateResult) > 0) {
            // Display the search results for dates
            echo "<table class='table table-bordered table-striped mt-5 text-center'>";
            echo "<thead class='thead-dark'>";
            echo "<tr><th>Student Name</th><th>Status</th><th>Date</th><th>BU No</th></tr>";
            echo "</thead>";
            echo "<tbody>";
            
            while ($row = mysqli_fetch_assoc($dateResult)) {
                echo "<tr>";
                echo "<td class='align-middle'>{$row['student_name']}</td>";
                $status = $row['status'];
                $badgeClass = '';
                
                switch ($status) {
                    case 'Present':
                        $badgeClass = 'success';
                        break;
                    case 'Absent':
                        $badgeClass = 'danger';
                        break;
                    case 'Late':
                        $badgeClass = 'warning';
                        break;
                    case 'Excused':
                        $badgeClass = 'info';
                        break;
                    default:
                        $badgeClass = 'secondary';
                        break;
                }
                
                echo "<td class='align-middle'><span class='badge rounded-pill text-bg-$badgeClass'>$status</span></td>";
                echo "<td class='align-middle'>" . date("M j, Y", strtotime($row['date'])) . "</td>";
                echo "<td class='align-middle'>{$row['bu_no']}</td>";
                echo "</tr>";
            }
            
            echo "</tbody>";
            echo "</table>";
            

        } else {
            echo "<div class='alert alert-info' role='alert'>
            No matching results found for the date: $searchKey</div>";
        }
    } else {
        echo "<div class='alert alert-info text-center' role='alert'>
        No search key provided.</div>";
    }
}
?>



<?php include('include/footer.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('include/modal.php'); ?>
</body>
</html>
