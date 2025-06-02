<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in and is either admin or employer
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ["admin", "superadmin", "employer"])) {
    header("Location: login.php");
    exit();
}

// Include common files
include "includes/head.php";
include "../../pages/includes/connection.php";
?>

<body>
    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Vlog</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item">Vlogs</li>
                    <li class="breadcrumb-item active">Add Vlog</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add New Vlog</h5>

                            <!-- Add Vlog Form -->
                            <form action="scripts/vlog-add.php" method="POST">
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link" class="col-sm-2 col-form-label">YouTube Link</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="link" name="link" required>
                                    </div>
                                </div>

                                <!-- Hidden status: default to 1 (visible) -->
                                <input type="hidden" name="status" value="1">

                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Add Vlog</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Add Vlog Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>
</body>
</html>
