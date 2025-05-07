<!DOCTYPE html>
<html lang="en">
<?php
session_start();

include "includes/head.php";
include "includes/connection.php";

$jobs_per_page = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1);
$offset = ($page - 1) * $jobs_per_page;

$query = "SELECT * FROM jobpostings WHERE status = '1' ORDER BY posted_at DESC LIMIT $jobs_per_page OFFSET $offset";
$result = $conn->query($query);

$total_jobs = $conn->query("SELECT COUNT(*) AS total FROM jobpostings WHERE status = '1'")->fetch_assoc()['total'];
$total_pages = ceil($total_jobs / $jobs_per_page);
?>

<body>

<?php include "includes/spinner.php" ?>
<?php include "includes/navbar.php" ?>
<?php include "includes/modal_search.php" ?>

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
                        <div class="blog-comment d-flex justify-content-between mb-3">
                            <div class="small"><span class="fa fa-calendar text-primary"></span>
                                <?= htmlspecialchars($row['posted_at']); ?></div>
                        </div>
                        <div class="service-content-inner">
                            <h4 class="mb-0"><?= htmlspecialchars($row['title']); ?>
                                <button type="button"
                                    class="btn btn-outline-primary btn-sm"><?= htmlspecialchars($row['job_type']); ?></button>
                            </h4>
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
            <?php if ($total_pages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Prev</span>
                        </a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">Next &raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include "includes/footer.php" ?>
<?php include "includes/copyright.php" ?>

<a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

<?php include "includes/script.php" ?>
</body>
</html>