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
$sql = "SELECT *, e.resume_path, e.employee_id, u.first_name, u.last_name, u.email, 
               u.phone, u.address, e.position, e.created_at
        FROM employees e
        LEFT JOIN users u ON e.user_id = u.user_id 
        WHERE e.employee_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id); // Corrected "j" to "i"
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
            <h1>Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                            <h2><?php echo htmlspecialchars($employee['first_name']); ?>
                                <?php echo htmlspecialchars($employee['last_name']); ?></h2>
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

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                        Profile</button>
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
                                            <?php echo htmlspecialchars($employee['position']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['email']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['phone']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['address']); ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Birthday</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo htmlspecialchars($employee['birthday']); ?></div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Resume</div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php if (!empty($employee['resume_path'])) : ?>
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
                                                            <p><?php echo htmlspecialchars($resume_path); ?></p>
                                                            <iframe src="/<?php echo htmlspecialchars($employee['resume_path']); ?>"
                                                                width="100%" height="1000px"
                                                                style="border: none;"></iframe>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php else : ?>
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

                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <form>

                                        <div class="row mb-3">
                                            <label for="currentPassword"
                                                class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="password" type="password" class="form-control"
                                                    id="currentPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New
                                                Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="newpassword" type="password" class="form-control"
                                                    id="newPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter
                                                New Password</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="renewpassword" type="password" class="form-control"
                                                    id="renewPassword">
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Change Password</button>
                                        </div>
                                    </form><!-- End Change Password Form -->

                                </div>

                            </div><!-- End Bordered Tabs -->

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