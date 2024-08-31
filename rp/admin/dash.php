<?php
// Include necessary files
include('include/header.php');
include('../connection.php');

// Initialize session (uncomment if using sessions)
// session_start();

// Define PAGE based on $_GET parameters
if (isset($_GET['dashboard'])) {
    define('PAGE', 'dashboard');
} elseif (isset($_GET['managesale'])) {
    define('PAGE', 'managesale');
} elseif (isset($_GET['addsale'])) {
    define('PAGE', 'addsale');
} elseif (isset($_GET['manageproduct'])) {
    define('PAGE', 'manageproduct');
} elseif (isset($_GET['manageprofit'])) {
    define('PAGE', 'manageprofit');
} elseif (isset($_GET['calculator'])) {
    define('PAGE', 'calculator');
} elseif (isset($_GET['logout'])) {
    define('PAGE', 'logout');
} else {
    // Default PAGE when no specific page is selected
    define('PAGE', 'dashboard'); // or define a default page here
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Add necessary CSS links -->
    <link rel="stylesheet" href="path/to/bootstrap.min.css">
    <!-- Add necessary JS links -->
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <!-- Add additional CSS or JS files as needed -->
</head>

<body>
        <nav class="navbar bg-secondary shadow no-print"><a href="" class="navbar-brand text-white mx-3">Success</a>
            <div class="float-end">
                <!-- <a href="additem.php" class="btn btn-outline-light text-info">Add Item</a>
            <a href="addcategory.php" class="btn btn-outline-light text-info me-3">Add Category</a> -->
            </div>
        </nav>
        <span class="text-center"></span>
        <div class="container-fluid mt-2">
            <div class="row">
                <nav class="col-sm-2 bg-light fixed no-print">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <li class="nav-item"><a href="dash.php?dashboard" class="nav-link text-dark text-center mt-2 <?php if (PAGE == 'dashboard') {
                                                                                                                                echo 'bg-dark text-white';
                                                                                                                            } ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li class="nav-item"><a href="dash.php?managesale" class="nav-link text-dark text-center mt-2 <?php if (PAGE == 'managesale') {
                                                                                                                                echo 'bg-dark text-white';
                                                                                                                            } ?>"><i class="fa-solid fa-comments-dollar me-2"></i>Manage Sale</a></li>
                            <!-- <li class="nav-item"><a href="dash.php?addsale" class="nav-link text-dark text-center mt-2 <?php if (PAGE == 'addsale') {
                                                                                                                            echo 'bg-dark text-white';
                                                                                                                        } ?>"><i class="fa-solid fa-pen-to-square me-2"></i>Add Sale</a></li> -->
                            <!-- <li class="nav-item"><a href="dash.php?addproduct" class="nav-link text-dark text-center mt-2"><i class="fa-solid fa-pen-to-square me-2"></i>Add Product</a></li> -->
                            <li class="nav-item"><a href="dash.php?manageproduct" class="nav-link text-dark text-center mt-2 <?php if (PAGE == 'manageproduct') {
                                                                                                                                echo 'bg-dark text-white';
                                                                                                                            } ?>"><i class="fa-solid fa-comment-dollar me-2"></i>Manage Product</a></li>
                            <li class="nav-item"><a href="dash.php?manageprofit" class="nav-link text-dark text-center mt-2 <?php if (PAGE == 'manageprofit') {
                                                                                                                                echo 'bg-dark text-white';
                                                                                                                            } ?>"><i class="fa-solid fa-comment-dollar me-2"></i>Manage Profit</a></li>
                            <li class="nav-item"><a href="dash.php?calculator" class="nav-link text-dark text-center mt-2 <?php if (PAGE == 'calculator') {
                                                                                                                                    echo 'bg-dark text-white';
                                                                                                                                } ?>"><i class="fa-solid fa-comment-dollar me-2"></i>Profit Calculator</a></li>
                            <li class="nav-item"><a href="logout.php?logout" class="nav-link text-dark text-center mt-2"><i class="fa-solid  fa-right-to-bracket me-2"></i>logout</a></li>

                        </ul>
                    </div>
                </nav>
                <?php
                if (isset($_GET['dashboard'])) {
                    include("dashboard.php");
                }
                if (isset($_GET['managesale'])) {
                    include("managesale.php");
                }
                if (isset($_GET['addsale'])) {
                    include("addsale.php");
                }
                if (isset($_GET['addproduct'])) {
                    include("addproduct.php");
                }
                if (isset($_GET['manageproduct'])) {
                    include("manageproduct.php");
                }
                if (isset($_GET['manageprofit'])) {
                    include("manageprofit.php");
                }
                if (isset($_GET['calculator'])) {
                    include("../user/calculator.php");
                }


                ?>




            </div>
        </div>