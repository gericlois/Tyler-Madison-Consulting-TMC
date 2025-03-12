<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) { // Ensure session is active
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "../../pages/includes/connection.php";

// Check if job ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid job listing.");
}

$job_id = intval($_GET['id']); // Sanitize input

// Fetch job details
$stmt = $conn->prepare("SELECT * FROM jobpostings j left join users u ON j.posted_by= u.user_id WHERE j.job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc(); // Now, $job is properly set
$stmt->close();

?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Job Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="jobs.php">Jobs</a></li>
                    <li class="breadcrumb-item active">Job Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">

                <div class="col-xl-12">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#applicants">
                                        Applicants</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                                    <p>
                                        <?php
                                            if ($job['status'] == "Active") {
                                                echo ' <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Active</span>';
                                            } else if ($job['status'] == "Inactive") {
                                                echo ' <span class="badge bg-primary"><i class="bi bi-exclamation-octagon me-1"></i> Inactive</span>';
                                            }
                                        ?>
                                    </p>

                                    <h5 class="card-title">Description:</h5>
                                    <p class="small"><?php echo htmlspecialchars($job['description']); ?></p>

                                    <h5 class="card-title">Job Posting Details</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Job Type:</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['job_type']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Salary:</div>
                                        <div class="col-lg-9 col-md-8"> $<?php echo htmlspecialchars($job['salary']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Schedule:</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['schedule']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Location:</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['location']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Skills:</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['skills']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Date Posted:</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($job['posted_at']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Deadline:</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['end_at']); ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Posted By:</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($job['first_name']); ?> <?php echo htmlspecialchars($job['last_name']); ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="applicants">

                                    <table class="table datatable">
                                        <thead>
                                            <tr>
                                                <th>Employee Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Applied Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                    $sql = "SELECT ja.jobapplication_id AS application_id, ja.created_at AS applied_at, 
                                                        u.user_id AS employee_id, u.first_name, u.last_name, u.email, u.phone
                                                    FROM jobapplications ja
                                                    INNER JOIN users u ON ja.employee_id = u.user_id
                                                    WHERE ja.job_id = $job_id
                                                    ORDER BY ja.created_at DESC";

                                                    $result = $conn->query($sql);
                                                    if ($result->num_rows > 0) {
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<tr>
                                                                <td>{$row['first_name']} {$row['last_name']}</td>
                                                                <td>{$row['email']}</td>
                                                                <td>{$row['phone']}</td>
                                                                <td>{$row['applied_at']}</td>
                                                                <td>
                                                                    <a href='employee-profile.php?id={$row['employee_id']}' class='btn btn-sm btn-success'>View Profile</a>
                                                                    <a href='scripts/remove-application.php?app_id={$row['application_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to remove this application?\")'>Remove</a>
                                                                </td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='6' class='text-center'>No employees have applied for this job yet.</td></tr>";
                                                    }
                                                    ?>
                                        </tbody>
                                    </table>

                                </div>



                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>