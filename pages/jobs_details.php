<!DOCTYPE html>
<html lang="en">
<?php
session_start();

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
$employee_id = $_SESSION['employee_id'] ?? null; // Assuming user_id is stored in session

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

<head>
    <meta charset="utf-8">
    <title>Job Openings in Life Sciences, Manufacturing & Automation | Tyler Madison Consulting</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta Description -->
    <meta name="description" content="Browse our latest job opportunities in pharmaceuticals, life sciences, manufacturing, and automation. Find contract, temporary, and permanent positions with top companies.">

    <!-- Meta Keywords (optional but can help for niche SEO) -->
    <meta name="keywords" content="staffing solutions, IQ OQ PQ protocols, computer system validation, DeltaV, Rockwell Automation, GAMP 5, FDA compliance, 21 CFR Part 11, clean in place CIP, steam in place SIP, quality management systems QMS, automation staffing, validation engineers">

    <link rel="icon" type="image/png" href="../../includes/img/tmc.ico"> 

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../includes/lib/animate/animate.min.css" rel="stylesheet" />
    <link href="../includes/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../includes/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../includes/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../includes/css/style.css" rel="stylesheet">
</head>


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
                <?php
                if ($job['status'] == 1) {
                    echo '<span class="badge bg-primary">Active</span>';
                } elseif ($job['status'] == 2) {
                    echo '<span class="badge bg-danger">Inactive</span>';
                } else {
                    echo '<span class="badge bg-warning">Pending</span>';
                }

                if ($applied) {
                    echo '<span class="badge bg-success ms-2">Applied</span>';
                }
                ?>
            </h4>
            <h1 class="display-4 mb-0">
                <?= htmlspecialchars($job['title']); ?>
            </h1>
            <p class="mb-0"><?= htmlspecialchars(date('F j, Y g:i A', strtotime($job['posted_at']))); ?></p>
        </div>

        <div class="row g-4 mb-4 justify-content-center">
            <div class="col-12 wow fadeInUp" data-wow-delay="0.2s">
                <div class="service-item">
                    <div class="service-content p-4">
                        <div class="service-content-inner">
                            <p><strong>Job Type:</strong> <?= htmlspecialchars($job['job_type']); ?></p>
                            <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary']); ?></p>
                            <p><strong>Schedule:</strong> <?= htmlspecialchars($job['schedule']); ?></p>
                            <p><strong>Location:</strong> <?= htmlspecialchars($job['location']); ?></p>
                            <p><strong>Skills Needed:</strong> <?= htmlspecialchars($job['skills']); ?></p>

                            <?php if (!empty($job['start_date'])): ?>
                                <p><strong>Start Date:</strong> <?= htmlspecialchars($job['start_date']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($job['duration'])): ?>
                                <p><strong>Duration:</strong> <?= htmlspecialchars($job['duration']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($job['hours'])): ?>
                                <p><strong>Working Hours:</strong> <?= htmlspecialchars($job['hours']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($job['preferred_skills'])): ?>
                                <p><strong>Preferred Skills:</strong> <?= htmlspecialchars($job['preferred_skills']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($job['education'])): ?>
                                <p><strong>Education Requirement:</strong> <?= htmlspecialchars($job['education']); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($job['responsibilities'])): ?>
                                <p><strong>Responsibilities:</strong><br> <?= nl2br(htmlspecialchars($job['responsibilities'])); ?></p>
                            <?php endif; ?>

                            <hr>
                            <p class="mb-0"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($job['description'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.2s">
                <?php if (!$applied): ?>
                    <?php if (!isset($_SESSION['employee_id'])): ?>
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="login.php">
                            Apply Now
                        </a>
                    <?php else: ?>
                        <a class="btn btn-primary rounded-pill py-3 px-5"
                           href="includes/scripts/jobsapplication.php?id=<?= $job['job_id']; ?>&status=1">
                            Apply Now
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <a href="javascript:history.back()" class="btn btn-success rounded-pill py-3 px-5 ms-2">
                    Back to Job Listing
                </a>
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