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
               e.status, e.created_at, e.profile_picture, e.resume_path
        FROM employees e
        LEFT JOIN users u ON e.user_id = u.user_id
        WHERE e.user_id = ?";


$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($employee_id, $first_name, $last_name, $email, $phone, $address, 
                   $position, $cover_letter, $link_facebook, $link_linkedin, $link_instagram, 
                   $status, $created_at, $profile_picture, $resume_path);

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
            <div class="col-lg-4">
                <div class="card shadow-lg">
                    <div class="card-body text-left">
                        <?php if (!empty($profile_picture)) : ?>
                        <!-- Show Profile Picture -->
                        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture"
                            class="rounded-circle img-fluid my-3" width="150" height="150">
                        <?php else : ?>
                        <!-- Default Profile Picture & Upload Form -->
                        <p>No Profile Picture</p>
                        <!-- Upload Profile Picture Form -->
                        <form action="includes/scripts/upload_profile.php" method="POST" enctype="multipart/form-data"
                            class="row justify-content-center">
                            <div class="col-3">
                                <input type="file" name="profile_picture" accept="image/*" required
                                    class="form-control">
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-success">Upload</button>
                            </div>
                        </form>
                        <hr>
                        <?php endif; ?>

                        <h2 class="card-title"><?php echo htmlspecialchars($first_name . " " . $last_name); ?>
                        <a href="profile-edit.php?user_id=<?php echo htmlspecialchars($user_id); ?>"
                            class="btn-sm btn-warning mt-3">
                            Edit Profile
                        </a></h2>

                        <hr>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
                        <p><strong>Position:</strong> <?php echo htmlspecialchars($position); ?></p>
                        <p><strong>Joined On:</strong> <?php echo htmlspecialchars($created_at); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card shadow-lg">
                    <div class="card-body text-left">
                        <!-- Cover Letter Section -->
                        <p><strong>Cover Letter:</strong></p>

                        <?php if (!empty($cover_letter)) : ?>
                        <p><?php echo nl2br(htmlspecialchars($cover_letter)); ?></p>
                        <?php else : ?>
                        <form action="includes/scripts/upload_cover_letter.php" method="POST">
                            <div class="mb-3">
                                <textarea name="cover_letter" class="form-control"
                                    placeholder="Write your cover letter here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Save Cover Letter</button>
                        </form>
                        <?php endif; ?>

                        <hr>

                        <!-- Resume Upload / View -->
                        <p><strong>Resume:</strong>

                            <?php if (!empty($resume_path)) : ?>
                            <!-- Button to Open Modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#resumeModal">
                                View Resume
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
                                        <iframe src="/<?php echo htmlspecialchars($resume_path); ?>" width="100%"
                                            height="1000px" style="border: none;"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else : ?>
                        <!-- Resume Upload Form -->
                        <form action="upload_resume.php" method="POST" enctype="multipart/form-data"
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