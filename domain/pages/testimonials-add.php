<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in and is either admin or employer
if (!isset($_SESSION["admin_id"]) || !in_array($_SESSION["role"], ["admin", "superadmin", "employer"])) {
    header("Location: login.php");
    exit();
}

// Include common files
include "includes/head.php";
include "../../pages/includes/connection.php";
?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php"; ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php"; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Testimonial</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="testimonials.php">Testimonials</a></li>
                    <li class="breadcrumb-item active">Add Testimonial</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <h5 class="card-title">New Testimonial Entry</h5>

                            <form action="scripts/testimonial-add.php" method="POST">
                                <div class="row mb-3">
                                    <label for="name" class="col-sm-3 col-form-label">Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="company" class="col-sm-3 col-form-label">Company</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="company" name="company" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="rate" class="col-sm-3 col-form-label">Rate (1-5)</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" id="rate" name="rate" required>
                                            <option value="">Select Rating</option>
                                            <option value="1">1 Star</option>
                                            <option value="2">2 Stars</option>
                                            <option value="3">3 Stars</option>
                                            <option value="4">4 Stars</option>
                                            <option value="5">5 Stars</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="testimonial" class="col-sm-3 col-form-label">Testimonial</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" id="testimonial" name="testimonial" rows="5" required></textarea>
                                    </div>
                                </div>

                                <input type="hidden" name="status" value="1"> <!-- Default: visible -->

                                <div class="row mb-3">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-primary">Submit Testimonial</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php"; ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php"; ?>

</body>
</html>
