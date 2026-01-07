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
$user_id = $_SESSION['user_id']; // The logged-in user

// Fetch employee details using JOIN
$sql = "SELECT e.employee_id, u.first_name, u.last_name, u.email, u.phone, u.address, 
               e.position, e.cover_letter, e.link_facebook, e.link_linkedin, e.link_instagram, 
               e.status, e.created_at, e.profile_picture, e.resume_path, e.cover_letter_path
        FROM employees e
        LEFT JOIN users u ON e.user_id = u.user_id
        WHERE e.user_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result(
    $employee_id,
    $first_name,
    $last_name,
    $email,
    $phone,
    $address,
    $position,
    $cover_letter,
    $link_facebook,
    $link_linkedin,
    $link_instagram,
    $status,
    $created_at,
    $profile_picture,
    $resume_path,
    $cover_letter_path
);

if ($stmt->fetch()) {
    // Now you can use these variables
}
$stmt->close();


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
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Profile</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">Profile</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->


    <!-- Profile Start -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <?php
            if (isset($_GET['success'])) {
                if ($_GET["success"] == "resume_uploaded") {
                    echo '
                                                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <strong>Success!</strong> Your resume has been uploaded successfully.  
                                                        You can now review your updated resume when you apply for jobs.  
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
                if ($_GET["success"] == "cover_letter_uploaded") {
                    echo '
                                                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                        <strong>Success!</strong> Your cover letter has been uploaded successfully.  
                                                        You can now review your updated resume when you apply for jobs.  
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
                if ($_GET["success"] == "IncorrectPassword") {
                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>The password is incorrect. Before logging in, make sure your password is correct.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
            }
            ?>
            <!-- Profile Section -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body text-left">
                        <?php if (!empty($profile_picture)): ?>
                            <!-- Show Profile Picture -->
                            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture"
                                class="rounded-circle img-fluid my-3" width="150" height="150">
                        <?php else: ?>
                            <!-- Default Profile Picture & Upload Form -->
                            <p>No Profile Picture</p>
                            <!-- Upload Profile Picture Form -->
                            <form action="includes/scripts/upload_profile.php" method="POST" enctype="multipart/form-data"
                                class="row justify-content-center">
                                <div class="col-7">
                                    <input type="file" name="profile_picture" accept="image/*" required
                                        class="form-control">
                                </div>
                                <div class="col-1">
                                    <button type="submit" class="btn btn-success">Upload</button>
                                </div>
                            </form>
                            <hr>
                        <?php endif; ?>

                        <h2 class="card-title"><?php echo htmlspecialchars($first_name . " " . $last_name); ?><br>
                            <a href="profile-edit.php?user_id=<?php echo htmlspecialchars($user_id); ?>"
                                class="btn-sm btn-warning rounded-pill py-2 px-4 ms-3 flex-shrink-0">
                                Edit Profile
                            </a>
                        </h2>

                        <hr>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($position); ?></p>
                        <p><strong>Joined On:</strong> <?php echo htmlspecialchars($created_at); ?></p>
                    </div>
                </div>
            </div>

            <!-- Cover Letter Section -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-lg">
                    <div class="card-body text-left">
                        <p><strong>Cover Letter:</strong>

                            <!-- Cover Letter Upload / View -->
                            <?php if (!empty($cover_letter_path)): ?>
                                <!-- Button to Open Modal -->
                                <button type="button" class="btn btn-primary rounded-pill py-2 px-4 ms-3 flex-shrink-0"
                                    data-bs-toggle="modal" data-bs-target="#coverLetterModal">
                                    View Cover Letter
                                </button>

                                <!-- Button to Change Cover Letter -->
                                <button type="button" class="btn btn-warning rounded-pill py-2 px-4 ms-2"
                                    id="changeCoverLetterBtn">
                                    Change Cover Letter
                                </button>
                        </p>
                        <!-- Cover Letter Modal -->
                        <div class="modal fade" id="coverLetterModal" tabindex="-1" aria-labelledby="coverLetterModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="coverLetterModalLabel">Cover Letter Preview</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                                        <iframe src="includes/scripts/<?php echo htmlspecialchars($cover_letter_path); ?>"
                                            width="100%" height="1000px" style="border: none;"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden Cover Letter Upload Form (Initially Hidden) -->
                        <form action="includes/scripts/upload_cover_letter.php" method="POST" enctype="multipart/form-data"
                            class="row justify-content-center mt-3" id="coverLetterUploadForm" style="display: none;">
                            <div class="col-5">
                                <input type="file" name="cover_letter" accept=".pdf" required class="form-control">
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-success">Upload</button>
                            </div>
                        </form>

                    <?php else: ?>
                        <!-- Cover Letter Upload Form (Default) -->
                        <form action="includes/scripts/upload_cover_letter.php" method="POST" enctype="multipart/form-data"
                            class="row justify-content-center">
                            <div class="col-5">
                                <input type="file" name="cover_letter" accept=".pdf" required class="form-control">
                            </div>
                            <div class="col-3">
                                <button type="submit" class="btn btn-success">Upload</button>
                            </div>
                        </form>
                    <?php endif; ?>


                    <hr>

                    <!-- Resume Upload / View -->
                    <p><strong>Resume:</strong>

                        <?php if (!empty($resume_path)): ?>
                            <!-- Button to Open Modal -->
                            <button type="button" class="btn btn-primary rounded-pill py-2 px-4 ms-3 flex-shrink-0"
                                data-bs-toggle="modal" data-bs-target="#resumeModal">
                                View Resume
                            </button>

                            <!-- Button to Change Resume -->
                            <button type="button" class="btn btn-warning rounded-pill py-2 px-4 ms-2"
                                id="changeResumeBtn">
                                Change Resume
                            </button>

                            <!-- Resume Modal -->
                    <div class="modal fade" id="resumeModal" tabindex="-1" aria-labelledby="resumeModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="resumeModalLabel">Resume Preview</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                                    <iframe src="includes/scripts/<?php echo htmlspecialchars($resume_path); ?>"
                                        width="100%" height="1000px" style="border: none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Resume Upload Form (Initially Hidden) -->
                    <form action="includes/scripts/upload_resume.php" method="POST" enctype="multipart/form-data"
                        class="row justify-content-center mt-3" id="resumeUploadForm" style="display: none;">
                        <div class="col-5">
                            <input type="file" name="resume" accept=".pdf" required class="form-control">
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-success">Upload</button>
                        </div>
                    </form>

                <?php else: ?>
                    <!-- Resume Upload Form (Default) -->
                    <form action="includes/scripts/upload_resume.php" method="POST" enctype="multipart/form-data"
                        class="row justify-content-center">
                        <div class="col-5">
                            <input type="file" name="resume" accept=".pdf" required class="form-control">
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-success">Upload</button>
                        </div>
                    </form>
                <?php endif; ?>
                </p>
                    </div>
                </div>
            </div>

            <!-- JavaScript to Show Resume Upload Form -->
            <script>
                document.getElementById('changeResumeBtn')?.addEventListener('click', function() {
                    document.getElementById('resumeUploadForm').style.display = 'flex';
                });
            </script>


            <div class="col-lg-12 mb-3">
                <div class="card shadow-lg">
                    <div class="card-body text-left">
                        <!-- My Jobs Section -->
                        <p><strong>My Jobs:</strong></p>
                        <hr>

                        <!-- Search Bar -->
                        <div class="mb-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search jobs...">
                        </div>

                        <?php
                        // Pagination settings
                        $limit = 8; // Jobs per page
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $start = ($page - 1) * $limit;

                        // Get total jobs count for pagination
                        $countQuery = "SELECT COUNT(*) AS total FROM jobapplications WHERE employee_id = ?";
                        $stmtCount = $conn->prepare($countQuery);
                        $stmtCount->bind_param("i", $user_id);
                        $stmtCount->execute();
                        $countResult = $stmtCount->get_result()->fetch_assoc();
                        $totalJobs = $countResult['total'];
                        $totalPages = ceil($totalJobs / $limit);
                        $stmtCount->close();

                        // Fetch jobs with limit for pagination
                        $query = "SELECT j.job_id, j.jobapplication_id, jp.title, jp.job_type, jp.schedule, jp.location, jp.salary, j.status, j.created_at, j.progress
                      FROM jobapplications j
                      JOIN jobpostings jp ON j.job_id = jp.job_id
                      WHERE j.employee_id = ? 
                      LIMIT ?, ?";

                        $stmt = $conn->prepare($query);
                        $stmt->bind_param("iii", $employee_id, $start, $limit);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>

                        <table class="table table-bordered" id="jobsTable">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Type</th>
                                    <th>Schedule</th>
                                    <th>Location</th>
                                    <th>Salary</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Applied Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><a
                                                    href="jobs_details.php?id=<?= $row['job_id']; ?>"><?= htmlspecialchars($row['title']); ?></a>
                                            </td>
                                            <td><?= htmlspecialchars($row['job_type']); ?></td>
                                            <td><?= htmlspecialchars($row['schedule']); ?></td>
                                            <td><?= htmlspecialchars($row['location'] ?? 'N/A'); ?></td>
                                            <td><?= $row['salary'] ? '$' . number_format($row['salary'], 2) : 'N/A'; ?></td>
                                            <td>
                                                <?php
                                                if ($row['progress'] == 0) {
                                                    echo '<span class="badge bg-primary">Applied</span>';
                                                } elseif ($row['progress'] == 1) {
                                                    echo '<span class="badge bg-warning">First Interview</span>';
                                                } elseif ($row['progress'] == 2) {
                                                    echo '<span class="badge bg-warning">Second Interview</span>';
                                                } elseif ($row['progress'] == 3) {
                                                    echo '<span class="badge bg-warning">Final Interview</span>';
                                                } elseif ($row['progress'] == 4) {
                                                    echo '<span class="badge bg-success">Hired</span>';
                                                } elseif ($row['progress'] == 5) {
                                                    echo '<span class="badge bg-danger">Decline</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">Pending</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($row['status'] == 1) {
                                                    echo '<span class="badge bg-primary">Active</span>';
                                                } elseif ($row['status'] == 2) {
                                                    echo '<span class="badge bg-danger">Inactive</span>';
                                                } else {
                                                    echo '<span class="badge bg-warning">Pending</span>';
                                                }
                                                ?>
                                            </td>
                                            <td><?= date("F d, Y", strtotime($row['created_at'])); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center">No job applications found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination">
                                <?php if ($page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?= $page + 1; ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- JavaScript for Search Function -->
            <script>
                document.getElementById('searchInput').addEventListener('keyup', function() {
                    let filter = this.value.toLowerCase();
                    let rows = document.querySelectorAll('#jobsTable tbody tr');

                    rows.forEach(row => {
                        let text = row.innerText.toLowerCase();
                        row.style.display = text.includes(filter) ? '' : 'none';
                    });
                });
            </script>
        </div>
    </div>
    <!-- Profile Section End -->

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