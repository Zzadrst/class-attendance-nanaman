<?php
require_once("icons.php");
?>

<style>
    .font {
        font-family: "Times New Roman", Times, serif;
    }
</style>

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow">
    <div class="container-fluid d-flex flex-column align-items-center">
        
        <div class="d-flex w-100 justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="img/bupc_logo.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-top">
                <div class="ms-3">
                    <h4 class="font m-0">BICOL UNIVERSITY</h4>
                    <h5 class="font m-0">POLANGUI CAMPUS</h5>
                    <h6 class="font m-0">Polangui, Albay</h6>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active text-dark js-navbar-close fw-bold" aria-current="page" href="homepage.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark js-navbar-close fw-bold" href="archive.php">ARCHIVE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark js-navbar-close fw-bold" href="class.php">CLASS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark js-navbar-close fw-bold" href="absentees.php">ABSENTEES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link user_btn_orange js-navbar-close fw-bold" href="contact.php">CONTACT US</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="logout.php">LOGOUT</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="w-100 text-center mt-2">
            <form class="d-inline-block w-50" role="search" action="search_results.php" method="get">
                <div class="input-group">
                    <input name="searchkey" class="form-control border-1 border-info rounded-pill" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-info rounded-pill shadow-none" type="submit"><?php echo ICONSEARCH; ?></button>
                </div>
            </form>
        </div>

    </div>
</nav>