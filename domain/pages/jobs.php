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
} ?>

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
                <a href="jobs-duplicate.php" class="btn btn-secondary rounded-pill">
                    <i class="bi bi-files me-1"></i> Duplicate Job
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
                                        <th>Applicants</th>
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
                                    WHERE jp.status = 1 $employer_id_filter
                                    ORDER BY jp.job_id DESC";


                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_text = ($row['status'] == "1") ? "Active" : "Inactive";

                                            $status_class = ($row['status'] == "Active") ? "bg-primary" : "bg-primary";

                                            echo "<tr>
                                        <td>00{$row['job_id']}</td>
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
                                                echo "<a href='scripts/job-update.php?id={$row['job_id']}&status=2' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to make the Job Posting Inactive?\")'>Deactivate</a>";

                                                if ($row['applicant_count'] > 0) {
                                                    echo " <a href='#' class='btn btn-sm btn-success' data-bs-toggle='modal' data-bs-target='#fillJobModal' data-job-id='{$row['job_id']}'>
                                                            Fill
                                                        </a>";
                                                }

                                            } else if ($row['status'] == "2") {
                                                echo "<a href='scripts/job-update.php?id={$row['job_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to make the Job Posting Active?\")'>Active</a>";
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
                            <!-- Fill Job Modal -->
                            <div class="modal fade" id="fillJobModal" tabindex="-1" aria-labelledby="fillJobModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="scripts/job-fill.php" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Fill Job Position</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="job_id" id="fillJobId">
                                                <div class="mb-3">
                                                    <label for="employeeSelect" class="form-label">Select
                                                        Employee</label>
                                                    <select name="employee_id" id="employeeSelect" class="form-select"
                                                        required>
                                                        <option value="">Loading applicants...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-success">Confirm Fill</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    var fillJobModal = document.getElementById('fillJobModal');

                                    fillJobModal.addEventListener('show.bs.modal', function (event) {
                                        var button = event.relatedTarget;
                                        var jobId = button.getAttribute('data-job-id');
                                        document.getElementById('fillJobId').value = jobId;

                                        var dropdown = document.getElementById('employeeSelect');
                                        dropdown.innerHTML =
                                            '<option value="">Loading applicants...</option>';

                                        fetch('scripts/fetch-applicants.php?job_id=' + jobId)
                                            .then(response => response.json())
                                            .then(data => {
                                                dropdown.innerHTML = '';
                                                if (data.length > 0) {
                                                    data.forEach(applicant => {
                                                        let option = document.createElement(
                                                            'option');
                                                        option.value = applicant.employee_id;
                                                        option.textContent = applicant
                                                            .employee_name;
                                                        dropdown.appendChild(option);
                                                    });
                                                } else {
                                                    dropdown.innerHTML =
                                                        '<option value="">No applicants found</option>';
                                                }
                                            })
                                            .catch(error => {
                                                console.error("Error fetching applicants:", error);
                                                dropdown.innerHTML =
                                                    '<option value="">Error loading applicants</option>';
                                            });
                                    });
                                });
                            </script>

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