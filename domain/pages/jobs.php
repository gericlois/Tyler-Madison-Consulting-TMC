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
            <h1>Jobs
                <a href="jobs-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Job
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Jobs</li>
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
                                if ($_GET["success"] == "JobUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The job posting has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["success"] == "StatusUpdated") {
                                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The job posting has been successfully updated!</b> Review the updated details to ensure accuracy.
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
                            <h5 class="card-title">Job Postings</h5>
                            <p>Manage and view all job postings in a structured table format. This section allows you to
                                track job listings, including titles, descriptions, locations, salaries, and posting
                                dates. </p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Location</th>
                                        <th>Salary</th>
                                        <th>Posted By</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Deadline</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT jp.job_id, jp.title, jp.description, jp.location, jp.salary, jp.end_at, jp.status,
                                            u.username AS posted_by_name, jp.posted_at 
                                            FROM jobpostings jp
                                            LEFT JOIN users u ON jp.posted_by = u.user_id 
                                            ORDER BY jp.job_id DESC";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_class = "bg-secondary"; 
                                            if ($row['status'] == "Active") {
                                                $status_class = "bg-primary";
                                            } elseif ($row['status'] == "Closed") {
                                                $status_class = "bg-danger";
                                            } elseif ($row['status'] == "Pending") {
                                                $status_class = "bg-warning";
                                            } elseif ($row['status'] == "Inactive") {
                                                $status_class = "bg-dark";
                                            }

                                            echo "<tr>
                                                <td>{$row['job_id']}</td>
                                                <td>{$row['title']}</td>
                                                <td>{$row['description']}</td>
                                                <td>{$row['location']}</td>
                                                <td>$" . number_format($row['salary'], 2) . "</td>
                                                <td>{$row['posted_by_name']}</td>
                                                <td>{$row['end_at']}</td>
                                                <td>{$row['posted_at']}</td>
                                                <td><span class='badge $status_class'>{$row['status']}</span></td>
                                                <td>
                                                    <a href='edit-job.php?id={$row['job_id']}' class='btn btn-sm btn-success'>View</a>
                                                    <a href='jobs-edit.php?id={$row['job_id']}' class='btn btn-sm btn-warning'>Edit</a>
                                                    <a href='delete-job.php?id={$row['job_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                                            
                                            if ($row['status'] == "Active") {
                                                echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=Inactive' class='btn btn-sm btn-dark'>Inactive</a>";
                                            }
                                            else if ($row['status'] == "Inactive") {
                                                echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=Active' class='btn btn-sm btn-primary'>Active</a>";
                                            }

                                            echo "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center'>No jobs found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>



                            <!-- End Table with stripped rows -->

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