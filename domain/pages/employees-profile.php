<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "../../pages/includes/connection.php";

// Validate employee ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>Invalid employee account.</p>";
    exit();
}

$employee_id = intval($_GET['id']); // Ensure ID is an integer

// Fetch employee details
$sql = "SELECT *, e.resume_path, e.employee_id, u.first_name, u.last_name, u.email, e.employee_id,
               u.phone, u.address, e.position, e.created_at, u.user_id as employee_id_two
        FROM employees e
        LEFT JOIN users u ON e.user_id = u.user_id 
        WHERE e.employee_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Employee not found.</p>";
    exit();
}

$employee = $result->fetch_assoc(); // Now, $employee is properly set
$stmt->close();

?>


<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Employee's Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="employees.php">Employee</a></li>
                    <li class="breadcrumb-item active">Employee's Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">


                            <img src="../../pages/<?php echo htmlspecialchars(string: $employee['profile_picture']); ?>"
                                alt="Profile Picture" class="rounded-circle img-fluid my-3" width="150" height="150">
                            <h2><?php echo htmlspecialchars($employee['first_name']); ?>
                                <?php echo htmlspecialchars($employee['last_name']); ?>


                                <a href='employees-edit.php?id=<?php echo htmlspecialchars($employee['employee_id']); ?>'
                                    class='btn btn-sm btn-success'>Edit</a>
                                <a href='#' class='btn btn-sm btn-warning' data-bs-toggle='modal'
                                    data-bs-target='#blockModal'
                                    data-employee-id='<?php echo htmlspecialchars($employee['employee_id']); ?>'>
                                    Block
                                </a>
                            </h2>
                            <h3><?php
                                if ($employee['status'] == "1") {
                                    echo ' <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Active</span>';
                                } else if ($employee['status'] == "2") {
                                    echo ' <span class="badge bg-primary"><i class="bi bi-exclamation-octagon me-1"></i> Inactive</span>';
                                }
                                ?></h3>
                            <div class="social-links mt-2">
                                <a href="<?php echo htmlspecialchars($employee['link_facebook']); ?>"
                                    class="facebook"><i class="bi bi-facebook"></i></a>
                                <a href="<?php echo htmlspecialchars($employee['link_instagram']); ?>"
                                    class="instagram"><i class="bi bi-instagram"></i></a>
                                <a href="<?php echo htmlspecialchars($employee['link_linkedin']); ?>"
                                    class="linkedin"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">Cover Leter</h5>
                                    <p class="small fst-italic"><?php echo htmlspecialchars($employee['last_name']); ?>
                                    </p>

                                    <h5 class="card-title">Profile Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Desired Position</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['position']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['email']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['phone']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['address']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Birthday</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['birthday']); ?>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Resume</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php if (!empty($employee['resume_path'])): ?>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#resumeModal">
                                                    View Resume
                                                </button>

                                                <div class="modal fade" id="resumeModal" tabindex="-1"
                                                    aria-labelledby="resumeModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="resumeModalLabel">Resume Preview
                                                                </h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body"
                                                                style="max-height: 80vh; overflow-y: auto;">
                                                                <iframe
                                                                    src="/<?php echo htmlspecialchars($employee['resume_path']); ?>"
                                                                    width="100%" height="1000px"
                                                                    style="border: none;"></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <p>No Resume</p>
                                            <?php endif; ?>

                                        </div>
                                    </div>



                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">



                                </div>

                                <div class="tab-pane fade pt-3" id="profile-settings">

                                    <!-- Settings Form -->
                                    <form>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email
                                                Notifications</label>
                                            <div class="col-md-8 col-lg-9">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="changesMade"
                                                        checked>
                                                    <label class="form-check-label" for="changesMade">
                                                        Changes made to your account
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="newProducts"
                                                        checked>
                                                    <label class="form-check-label" for="newProducts">
                                                        Information on new products and services
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="proOffers">
                                                    <label class="form-check-label" for="proOffers">
                                                        Marketing and promo offers
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="securityNotify"
                                                        checked disabled>
                                                    <label class="form-check-label" for="securityNotify">
                                                        Security alerts
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form><!-- End settings Form -->

                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Job List</h5>

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
                                        <th>Applicants</th>
                                        <th>Status</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $sql = "SELECT jp.job_id, jp.title, jp.description, jp.location, jp.salary, jp.end_at, jp.status, 
                                            u.username AS posted_by_name, jp.posted_at, e.name as employer_name, e.employer_id,
                                            ja.progress,
                                            (SELECT COUNT(*) FROM jobapplications ja2 WHERE ja2.job_id = jp.job_id) AS applicant_count
                                        FROM jobpostings jp
                                        LEFT JOIN users u ON jp.posted_by = u.user_id 
                                        LEFT JOIN employers e ON jp.employer_id = e.employer_id
                                        INNER JOIN jobapplications ja ON ja.job_id = jp.job_id
                                        WHERE ja.employee_id = ? 
                                        ORDER BY jp.job_id DESC";

                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("i", $employee_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    $progress_options = [
                                        0 => "Applied",
                                        1 => "First Interview",
                                        2 => "Second Interview",
                                        3 => "Final Interview",
                                        4 => "Hired",
                                        5 => "Declined",
                                        6 => "Filled"
                                    ];

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_text = ($row['status'] == "1") ? "Active" : "Inactive";
                                            $status_class = ($row['status'] == "1") ? "bg-primary" : "bg-secondary";
                                            $progress_text = $progress_options[$row['progress']] ?? "Unknown";

                                            echo "<tr>
                                            <td>100{$row['job_id']}</td>
                                            <td><a href='jobs-profile.php?id={$row['job_id']}' class='fw-bold text-decoration-none'>" . htmlspecialchars($row['title']) . "</a></td>
                                            <td>{$row['location']}</td>
                                            <td>$" . number_format($row['salary'], 2) . "</td>
                                            <td><a href='employers-profile.php?id={$row['employer_id']}'>" . htmlspecialchars($row['employer_name']) . "</a></td>
                                            <td>{$row['posted_at']}</td>
                                            <td>{$row['end_at']}</td>
                                            <td>{$row['applicant_count']}</td>
                                            <td><span class='badge $status_class'>{$status_text}</span></td>
                                            <td><span class='badge bg-info'>{$progress_text}</span></td>
                                            ";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='11' class='text-center'>No Applied jobs found</td></tr>";
                                    }

                                    $stmt->close();
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