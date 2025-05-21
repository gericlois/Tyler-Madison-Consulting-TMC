<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "../../pages/includes/connection.php";

// Capture employer ID if role is employer
$employer_id_filter = "";
if ($_SESSION["role"] === "employer" && isset($_SESSION["employer_id"])) {
    $employer_id = intval($_SESSION["employer_id"]);
    $employer_id_filter = "AND jp.employer_id = $employer_id";
}
 ?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Inactive Jobs
                <a href="jobs-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Job
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Inactive Jobs</li>
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
                            <h5 class="card-title">Inactive Job Postings</h5>
                            <p>Manage and view all job postings in a structured table format. This section allows you to
                                track job listings, including titles, descriptions, locations, salaries, and posting
                                dates. </p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Title</th>
                                        <th>Location</th>
                                        <th>Salary</th>
                                        <th>Employer</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Deadline</th>
                                        <th>Status</th>
                                        <th>Applicants</th> <!-- Added Applicants Column -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT jp.job_id, jp.title, jp.description, jp.location, jp.salary, jp.end_at, jp.status, 
                                        u.username AS posted_by_name, jp.posted_at, e.name as employer_name, e.employer_id,
                                        (SELECT COUNT(*) FROM jobapplications ja WHERE ja.job_id = jp.job_id) AS applicant_count
                                    FROM jobpostings jp
                                    LEFT JOIN users u ON jp.posted_by = u.user_id 
                                    LEFT JOIN employers e ON jp.employer_id = e.employer_id
                                    WHERE jp.status = 2 $employer_id_filter
                                    ORDER BY jp.job_id DESC";


                                                                $result = $conn->query($sql);

                                                                if ($result->num_rows > 0) {
                                                                    while ($row = $result->fetch_assoc()) {
                                                                        // Convert numeric status to text
                                                                        $status_text = ($row['status'] == "1") ? "Active" : "Inactive";

                                                                        // Assign class based on status
                                                                        $status_class = ($row['status'] == "Active") ? "bg-primary" : "bg-danger";

                                                                        echo "<tr>
                                            <td>100{$row['job_id']}</td>
                                            <td>
                                                <a href='jobs-profile.php?id={$row['job_id']}' class='fw-bold text-decoration-none'>
                                                    " . htmlspecialchars($row['title']) . "
                                                </a>
                                            </td>
                                            <td>{$row['location']}</td>
                                            <td>$" . number_format($row['salary'], 2) . "</td>
                                            <td>
                                                <a href='employers-profile.php?id=" . $row['employer_id'] . "'>
                                                    " . htmlspecialchars($row['employer_name']) . "
                                                </a>
                                            </td>
                                            <td>{$row['posted_at']}</td>
                                            <td>{$row['end_at']}</td>
                                            <td><span class='badge $status_class'>{$status_text}</span></td>
                                            <td>{$row['applicant_count']}</td>
                                            <td>";

                                            if ($row['status'] == "1") {
                                                echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=2' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to make the Job Posting Inactive?\")'>Inactive</a>";
                                            } else if ($row['status'] == "2") {
                                                echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to make the Job Posting Active?\")'>Active</a>";
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