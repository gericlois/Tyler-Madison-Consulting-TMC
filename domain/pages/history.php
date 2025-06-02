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
            <h1>Settings
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Settings</li>
                </ol>
            </nav>
            <?php
            if (isset($_GET['success'])) {
                if ($_GET["success"] == "JobAdded") {
                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>A new job posting has been added! Review the details of the posted job.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
            }
            ?>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Activity Section -->
                            <h5 class="card-title">Admin Activity</h5>
                            <p>View recent actions performed by admins on the job postings.</p>

                            <!-- Table to display activity -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Activity ID</th>
                                        <th>Admin</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $activity_sql = "SELECT a.activity_id, a.action, a.description, a.timestamp, u.user_id, u.first_name, u.last_name
                                    FROM activity a
                                    LEFT JOIN users u ON a.user_id = u.user_id
                                    ORDER BY a.timestamp DESC";

                                                            $activity_result = $conn->query($activity_sql);

                                                            if ($activity_result->num_rows > 0) {
                                                                while ($activity_row = $activity_result->fetch_assoc()) {
                                                                    $user_name = htmlspecialchars($activity_row['first_name'] . " " . $activity_row['last_name']);
                                                                    $user_id = $activity_row['user_id'];
                                                                    echo "<tr>
                                            <td>{$activity_row['activity_id']}</td>
                                            <td><a href='users-profile.php?id={$user_id}' class='fw-bold text-decoration-none'>{$user_name}</a></td>
                                            <td>{$activity_row['action']}</td>
                                            <td>{$activity_row['description']}</td>
                                            <td>{$activity_row['timestamp']}</td>
                                        </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No activity found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

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