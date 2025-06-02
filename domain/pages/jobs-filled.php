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
            <h1>Jobs
                <a href="jobs-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Job
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
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
                                        <th>Location</th>
                                        <th>Salary</th>
                                        <th>Employer</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Deadline</th>
                                        <th>Status</th>
                                        <th>Applicant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT 
                                                jp.job_id, 
                                                jp.title, 
                                                jp.description, 
                                                jp.location, 
                                                jp.salary, 
                                                jp.end_at, 
                                                jp.status, 
                                                jp.posted_at, 
                                                u.username AS posted_by_name, 
                                                e.name AS employer_name, 
                                                e.employer_id,
                                                ja.employee_id,
                                                emp_user.first_name AS hired_first_name,
                                                emp_user.last_name AS hired_last_name,
                                                (
                                                    SELECT COUNT(*) 
                                                    FROM jobapplications ja2 
                                                    WHERE ja2.job_id = jp.job_id
                                                ) AS applicant_count
                                            FROM jobpostings jp
                                            LEFT JOIN users u ON jp.posted_by = u.user_id 
                                            LEFT JOIN employers e ON jp.employer_id = e.employer_id
                                            LEFT JOIN jobapplications ja ON ja.job_id = jp.job_id AND ja.progress = 6
                                            LEFT JOIN employees emp ON ja.employee_id = emp.employee_id
                                            LEFT JOIN users emp_user ON emp.user_id = emp_user.user_id
                                            WHERE jp.status = 3 $employer_id_filter
                                            ORDER BY jp.job_id DESC";




                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_text = ($row['status'] == "1") ? "Active" : "Inactive";

                                            $status_class = ($row['status'] == "Active") ? "bg-primary" : "bg-primary";

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
                                                    <td><span class='badge bg-primary'>Hired</span></td>
                                                  <td>
                                                        " . ($row['employee_id'] ? 
                                                            "<a href='employees-profile.php?id={$row['employee_id']}' class='text-primary fw-bold'>" . 
                                                                htmlspecialchars($row['hired_first_name'] . ' ' . $row['hired_last_name']) . 
                                                            "</a>" 
                                                            : "<i>No employee selected</i>") . "
                                                    </td>

                                                </tr>";
                                        }
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