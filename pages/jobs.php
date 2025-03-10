<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
} else {
    include "includes/head.php";
    include "includes/connection.php";
}

$query = "SELECT * FROM jobpostings WHERE status = 'active' ORDER BY posted_at DESC";
$result = $conn->query($query);
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
                <li class="breadcrumb-item active text-primary">Jobs</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->


    <!-- Jobs Start -->
    <div class="container-fluid service py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Active Job Listings</h4>
                <h1 class="display-4 mb-4">Explore Job Opportunities</h1>
                <p class="mb-0">Find the best job opportunities that match your skills and expertise.</p>
            </div>
            <div class="row g-4 mb-4 justify-content-center">
                <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-12 col-lg-12 col-xl-6 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="service-item">
                        <div class="service-content p-4">
                            <div class="service-content-inner">
                                <h4 class="mb-0"><?= htmlspecialchars($row['title']); ?>
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($row['job_type']); ?></button>
                                </h4>
                                <p class="mb-0"><i>Posted on <?= htmlspecialchars($row['posted_at']); ?></i></p>
                                <p class="mb-0">$<?= htmlspecialchars($row['salary']); ?></p>
                                <p class="mb-4"><?= substr(htmlspecialchars($row['description']), 0, 100); ?>...</p>
                                <a class="btn btn-primary rounded-pill py-2 px-4"
                                    href="jobs_details.php?id=<?= $row['job_id']; ?>">See More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.2s">
                <a class="btn btn-primary rounded-pill py-3 px-5" href="all_jobs.php">More Jobs</a>
            </div>
        </div>
    </div>
    <!-- Jobs End -->

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