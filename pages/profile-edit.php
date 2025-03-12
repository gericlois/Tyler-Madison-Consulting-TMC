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
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch employee data
    $query = "SELECT e.employee_id, u.first_name, u.last_name, u.email, u.phone, u.address, 
                     e.position, e.cover_letter, e.link_facebook, e.link_linkedin, e.link_instagram, 
                     e.status, e.created_at, e.profile_picture, e.resume_path
              FROM employees e
              LEFT JOIN users u ON e.user_id = u.user_id
              WHERE e.user_id = ?";  // Fixed WHERE clause

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id); // Fixed variable name
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();

    if (!$employee) {
        echo "Employee not found!";
        exit();
    }
} else {
    echo "Invalid request!";
    exit();
}
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
                <li class="breadcrumb-item"><a href="profile.php">Profile</a></li>
                <li class="breadcrumb-item active text-primary">Profile edit</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->


    <!-- Profile Edit Start -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-body text-left">
                        <h2>Edit Profile</h2>
                        <hr>
                        <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="employee_id"
                                value="<?php echo htmlspecialchars($employee['employee_id']); ?>">

                            <!-- First Name & Last Name -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                                </div>
                            </div>

                            <!-- Email & Phone -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                                </div>
                            </div>

                            <!-- Address & Position -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control"
                                        value="<?php echo htmlspecialchars($employee['address']); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Position</label>
                                    <input type="text" name="position" class="form-control"
                                        value="<?php echo htmlspecialchars($employee['position']); ?>" required>
                                </div>
                            </div>

                            <!-- Cover Letter -->
                            <div class="mb-3">
                                <label class="form-label">Cover Letter</label>
                                <textarea name="cover_letter" class="form-control"
                                    rows="4"><?php echo htmlspecialchars($employee['cover_letter']); ?></textarea>
                            </div>
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="employee_profile.php?employee_id=<?php echo $employee_id; ?>"
                                class="btn btn-secondary">Cancel</a>
                        </form>
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