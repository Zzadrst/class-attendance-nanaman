<?php
session_start();
include_once("include/connect.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAS - CLASS</title>
    <link rel="icon" type="image/png" href="weblogo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iK7t9QQvR1ciRDJC2L/HzIq1qVRyHh4eZL2M/iPh47Ha6Q5iS9x2lVO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/user.css">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css">
    <?php include('include/link.php')?>
</head>
<body>
    <?php include('include/nav.php') ?>

    <div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">CLASS</h2>
    <div class="h-line bg-dark col-12"></div>
    <p class="text-center mt-3">
        A class is the foundation of the learning process, providing a structured environment where students can acquire knowledge and skills. 
        It fosters interaction between students and instructors, allowing for the exchange of ideas, feedback, and collaborative learning. 
        Regular attendance in classes ensures that students stay on track with their studies, actively participate in discussions, and complete their coursework efficiently.
    </p>
</div>

    <div class="container">
        <div class="row">
            <div class="text-center d-flex justify-content-center col-12 mb-5">
                <button type="button" class="btn btn-info rounded-pill" data-bs-toggle="modal" data-bs-target="#createClassModal">
                    CREATE CLASS
                </button>          
            </div>
        </div>
    </div>

    <div class="modal fade" id="createClassModal" tabindex="-1" aria-labelledby="createClassModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createClassModalLabel">Create Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form method="post" action="create_class.php">
                    <div class="mb-3">
                        <label for="blockName" class="form-label">Block Name</label>
                        <input type="text" class="form-control" id="blockName" name="block_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="schoolYear" class="form-label">School Year</label>
                        <input type="text" class="form-control" id="schoolYear" name="school_year" pattern="\d{4} - \d{4}" placeholder="yyyy - yyyy" required>
                    </div>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <button type="submit" class="btn btn-info">Create Class</button>
                </form>


                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
        <?php
    $sql = "SELECT `block_id`, `block_name`, `user_id`, `school_yr` FROM `block` WHERE `user_id` = '{$_SESSION['user_id']}' ORDER BY `school_yr` DESC";
    $result = $conn->query($sql);
    $currentYear = null;

    // Check if there are any rows in the result
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $blockYear = $row['school_yr'];
            if ($blockYear != $currentYear) {
                // If the year changes, display the year as a header
                if ($currentYear !== null) {
                    echo '</div>'; // Close the previous row if it's not the first iteration
                }
                echo '<div class="row mb-3">';
                echo '<div class="col-12"><h3 class="text-center">School year ' . $blockYear . ' blocks</h3></div>';
                $currentYear = $blockYear;
            }
           
            // Display a button for each row
            echo '<div class="col-md-4 mb-3">
                <form method="get" action="process_class.php">
                    <input type="hidden" name="block_id" value="' . $row['block_id'] . '">
                    <input type="hidden" name="user_id" value="' . $row['user_id'] . '">
                    <button type="submit" class="btn btn-warning d-block w-100 rounded-pill">' . $row['block_name'] . '</button>  
                </form>
            </div>';
        }
        echo '</div>'; // Close the last row
    } else {
        // Display a message if there are no rows
        echo '<div class="col-12 text-center">No classes created</div>';
    }
?>


        </div>
    </div>

    <?php require('include/footer.php'); ?>
    <?php include('include/modal.php'); ?>
</body>
</html>
