<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
  header("Location: login.php");
} else {
  include "includes/head.php";
  include "../../pages/includes/connection.php";
}?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Blank</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Blank</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Job Posting</h5>

                            <!-- Add Job -->
                            <form action="scripts/job-add.php" method="POST">
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Job Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Job Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            required></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="location" name="location" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="salary" class="col-sm-2 col-form-label">Salary</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="salary" name="salary" step="0.01"
                                            required>
                                    </div>
                                </div>

                                <input type="hidden" id="posted_by" name="posted_by" value="<?php echo $_SESSION["admin_id"]; ?>">

                                <div class="row mb-3">
                                    <label for="deadline" class="col-sm-2 col-form-label">Application Deadline</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control" id="deadline" name="deadline">
                                    </div>
                                </div>

                                <!-- Status Field with Default Value "Active" -->
                                <div class="row mb-3">
                                    <label for="status" class="col-sm-2 col-form-label">Status</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="status" name="status" value="Active"
                                            readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Submit Job</button>
                                    </div>
                                </div>
                            </form>

                            <!-- End Add Job -->

                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Job Posting</h5>

                            <!-- Polar Area Chart -->
                            <canvas id="polarAreaChart" style="max-height: 400px;"></canvas>
                            <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#polarAreaChart'), {
                                    type: 'polarArea',
                                    data: {
                                        labels: [
                                            'Red',
                                            'Green',
                                            'Yellow',
                                            'Grey',
                                            'Blue'
                                        ],
                                        datasets: [{
                                            label: 'My First Dataset',
                                            data: [11, 16, 7, 3, 14],
                                            backgroundColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(75, 192, 192)',
                                                'rgb(255, 205, 86)',
                                                'rgb(201, 203, 207)',
                                                'rgb(54, 162, 235)'
                                            ]
                                        }]
                                    }
                                });
                            });
                            </script>
                            <!-- End Polar Area Chart -->

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