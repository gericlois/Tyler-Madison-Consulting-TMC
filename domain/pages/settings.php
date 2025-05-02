<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
} else {
    include "includes/head.php";
    include "../../pages/includes/connection.php";
} ?>

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


                <!-- Vlogs Section -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title">Vlogs
                                <a href="vlogs-add.php" class="btn btn-primary rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Add Vlogs
                                </a>
                            </h5>

                            <!-- Table to display Vlogs -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Vlog ID</th>
                                        <th>Title</th>
                                        <th>Link</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $vlogs = "SELECT * FROM `vlogs` ORDER BY created_at DESC";
                                    $activity_result = $conn->query($vlogs);

                                    if ($activity_result->num_rows > 0) {
                                        while ($activity_row = $activity_result->fetch_assoc()) {
                                            $statusText = $activity_row['status'] == 1 ? 'Visible' : 'Hidden';
                                            $toggleAction = $activity_row['status'] == 1 ? 0 : 1;
                                            $buttonLabel = $activity_row['status'] == 1 ? 'Hide' : 'Show';
                                            $buttonClass = $activity_row['status'] == 1 ? 'btn-danger' : 'btn-success';

                                            echo "<tr>
                                <td>{$activity_row['vlogs_id']}</td>
                                <td>{$activity_row['title']}</td>
                                <td><a href='{$activity_row['link']}' target='_blank'>View</a></td>
                                <td>{$statusText}</td>
                                <td>{$activity_row['created_at']}</td>
                                <td>
                                    <a href='scripts/vlog-update.php?id={$activity_row['vlogs_id']}&status={$toggleAction}' 
                                       class='btn btn-sm {$buttonClass}' 
                                       onclick='return confirm(\"Are you sure you want to {$buttonLabel} this vlog?\")'>
                                       {$buttonLabel}
                                    </a>
                                </td>
                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No Vlogs Found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <!-- Testimonials Section -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title">Testimonials
                                <a href="testimonials-add.php" class="btn btn-primary rounded-pill">
                                    <i class="bi bi-plus-circle me-1"></i> Add Testimonials
                                </a>
                            </h5>

                            <!-- Table to display Testimonials -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Testimonial ID</th>
                                        <th>Name</th>
                                        <th>Testimonial</th>
                                        <th>Company</th>
                                        <th>Rate</th>
                                        <th>Email</th>
                                        <th>Date Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $vlogs = "SELECT * FROM `testimonials`";
                                    $activity_result = $conn->query($vlogs);

                                    if ($activity_result->num_rows > 0) {
                                        while ($testimonial_row = $activity_result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$testimonial_row['testimonial_id']}</td>
                                                    <td>{$testimonial_row['name']}</td>
                                                    <td>{$testimonial_row['testimonial']}</td>
                                                    <td>{$testimonial_row['company']}</td>
                                                    <td>{$testimonial_row['rate']}</td>
                                                    <td>{$testimonial_row['email']}</td>
                                                    <td>{$testimonial_row['created_at']}</td>
                                                    <td>";

                                            // Show Display/Hide button based on status
                                            if ($testimonial_row['status'] == 0) {
                                                echo "<a href='scripts/testimonial-update.php?id={$testimonial_row['testimonial_id']}&status=1' 
                                                            class='btn btn-sm btn-success' 
                                                            onclick='return confirm(\"Are you sure you want to display this testimonial?\")'>
                                                            Display
                                                        </a>";
                                            } elseif ($testimonial_row['status'] == 1) {
                                                echo "<a href='scripts/testimonial-update.php?id={$testimonial_row['testimonial_id']}&status=0' 
                                                            class='btn btn-sm btn-danger' 
                                                            onclick='return confirm(\"Are you sure you want to hide this testimonial?\")'>
                                                            Hide
                                                        </a>";
                                            }

                                            echo "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No Testimonials Found</td></tr>";
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