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
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item">Employer</li>
                    <li class="breadcrumb-item active">Employer Add</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Employer</h5>
                            <?php
            if (isset($_GET['error'])) {
                if ($_GET["error"] == "EmailDuplicate") {
                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                            <b>A employer email is alread taken, pick another email.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
            }
            ?>

                            <!-- Add Employer Form -->
                            <form action="scripts/employer-add.php" method="POST" enctype="multipart/form-data">

                                <div class="row mb-3">
                                    <label for="name" class="col-sm-2 col-form-label">Employer Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="profile_picture" class="col-sm-2 col-form-label">Profile Picture</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="profile_picture"
                                            name="profile_picture" accept="image/*">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Cellphone Number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            required></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link_facebook" class="col-sm-2 col-form-label">Facebook</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="link_facebook" name="link_facebook">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link_linkedin" class="col-sm-2 col-form-label">LinkedIn</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="link_linkedin" name="link_linkedin">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link_instagram" class="col-sm-2 col-form-label">Instagram</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="link_instagram"
                                            name="link_instagram">
                                    </div>
                                </div>

                                <!-- Status Field (Default: Active) -->
                                <input type="hidden" id="status" name="status" value="1">

                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Add Employer</button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Add Employer Form -->

                        </div>
                    </div>
                </div>

                <?php
                $userCounts = [];
                $monthLabels = [];

                $sql = "SELECT 
                MONTH(created_at) AS month, 
                COUNT(*) AS count 
                FROM employers 
                GROUP BY MONTH(created_at) 
                ORDER BY MONTH(created_at)";
                $result = $conn->query($sql);

                
                $monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
                while ($row = $result->fetch_assoc()) {
                    $monthNumber = (int)$row['month'];
                    $monthLabels[] = $monthNames[$monthNumber];
                    $userCounts[] = (int)$row['count'];
                }
                ?>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bar Chart</h5>

                            <!-- Bar Chart -->
                            <canvas id="barChart" style="max-height: 400px;"></canvas>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#barChart'), {
                                        type: 'bar',
                                        data: {
                                            labels: <?= json_encode($monthLabels); ?>,
                                            datasets: [{
                                                label: 'Employer Added',
                                                data: <?= json_encode($userCounts); ?>,
                                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                                borderColor: 'rgba(54, 162, 235, 1)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>

                            <!-- End Bar CHart -->

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