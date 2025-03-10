<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["user_id"])) { // Ensure correct session variable
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "includes/connection.php"; // Make sure the connection is open

// Check if job ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid job listing.");
}

$job_id = intval($_GET['id']); // Sanitize input

// Fetch job details
$stmt = $conn->prepare("SELECT * FROM jobpostings WHERE job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc();
$stmt->close(); // Close statement but NOT connection

// Check if the user has applied for this job
$applied = false;
$employee_id = $_SESSION['user_id'] ?? null; // Assuming user_id is stored in session

if ($employee_id) {
    $stmt = $conn->prepare("SELECT * FROM jobapplications WHERE job_id = ? AND employee_id = ?");
    $stmt->bind_param("ii", $job_id, $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $applied = true;
    }
    $stmt->close(); // Close only statement
}

$conn->close(); // Now close connection after all queries are done
?>




<body>

    <!-- Spinner Start -->
    <?php include "includes/spinner.php" ?>
    <!-- Spinner End -->

    <!-- Navbar & Hero Start -->
    <?php include "includes/navbar.php" ?>
    <!-- Navbar & Hero End -->

    <!-- Modal Search Start -->
    <?php include "includes/modal_search.php" ?>
    <!-- Modal Search End -->


    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Jobs</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item"><a href="jobs.php">Jobs</a></li>
                <li class="breadcrumb-item active text-primary">Job Details</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->


    <!-- Jobs Details Start -->
    <div class="container-fluid service py-5">
        <div class="container py-2">
            <div class="text-center mx-auto pb-3 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 1200px;">
                <h4 class="text-primary">
                <span class="badge bg-primary"><?= htmlspecialchars($job['status']); ?></span>
                    <?php if ($applied): ?>
                    <span class="badge bg-success">Applied</span>
                    <?php endif; ?>
                </h4>
                <h1 class="display-4 mb-0">
                    <?= htmlspecialchars($job['title']); ?>
                </h1>
                <p class="mb-0"><?= htmlspecialchars($job['posted_at']); ?></p>
            </div>

            <div class="row g-4 mb-4 justify-content-center">
                <div class="col-12 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="service-item">
                        <div class="service-content p-4">
                            <div class="service-content-inner">
                                <p><strong>Job Type:</strong> <?= htmlspecialchars($job['job_type']); ?></p>
                                <p><strong>Salary:</strong> $<?= htmlspecialchars($job['salary']); ?></p>
                                <p><strong>Schedule:</strong> <?= htmlspecialchars($job['schedule']); ?></p>
                                <p><strong>Location:</strong> <?= htmlspecialchars($job['location']); ?></p>
                                <p class="mb-4"> <?= nl2br(htmlspecialchars($job['description'])); ?> </p>
                                <p><strong>Skills Needed:</strong> <?= htmlspecialchars($job['skills']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.2s">
                    <?php if (!$applied): ?>
                    <a class="btn btn-primary rounded-pill py-3 px-5"
                        href="includes/scripts/jobsapplication.php?id=<?= $job['job_id']; ?>&status=1">Apply Now</a>
                    <?php endif; ?>
                    <a href="javascript:history.back()" class="btn btn-success rounded-pill py-3 px-5">Back to
                        Job Listing</a>
                </div>
            </div>
        </div>
    </div>


    <!-- Jobs Details End -->

    <!-- Footer Start -->
    <?php include "includes/footer.php" ?>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <?php include "includes/copyright.php" ?>
    <!-- Copyright End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <?php include "includes/script.php" ?>
</body>

</html>