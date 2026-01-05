<!DOCTYPE html>
<html lang="en">
<?php
session_start();
  include "includes/head.php";
  include "includes/connection.php";
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
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Login</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">Login</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->


    <!-- Signup Start -->
    <div class="container-fluid contact bg-light py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <div class="card login-card">
                        <div class="card-body ">
                            <div class="text-center pb-4">
                                <h4 class="text-primary">Sign Up</h4>
                                <h2 class="mb-3">Get Started With Us!</h2>
                                <p class="mb-4">Sign up as an employer to manage your company profile and access hiring tools.</p>
                            </div>
                            <?php
                            if (isset($_GET['error'])) {
                                if ($_GET["error"] == "usernametaken") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Username has been taken, select another username!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "emailtaken") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Email has been taken, select another email!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
                            <hr>
                            <form action="includes/scripts/signup_employer.php" method="POST" enctype="multipart/form-data">

                                <div class="row mb-3">
                                    <label for="name" class="col-sm-2 col-form-label">Employer Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="profile_picture" class="col-sm-2 col-form-label">Profile Picture</label>
                                    <div class="col-sm-10">
                                        <input type="file" class="form-control" id="profile_picture"
                                            name="profile_picture" accept="image/*">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Username</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Cellphone Number</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            required></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link_facebook" class="col-sm-2 col-form-label">Facebook</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="link_facebook" name="link_facebook">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link_linkedin" class="col-sm-2 col-form-label">LinkedIn</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="link_linkedin" name="link_linkedin">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="link_instagram" class="col-sm-2 col-form-label">Instagram</label>
                                    <div class="col-sm-10">
                                        <input type="url" class="form-control" id="link_instagram"
                                            name="link_instagram">
                                    </div>
                                </div>

                                <!-- Status Field (Default: Active) -->
                                <input type="hidden" id="status" name="status" value="1">
                                
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Sign Up Employer</button>
                                    </div>
                            </form>


                            <p class="mt-4 text-center">Already have an account? <a href="login.php"
                                    class="text-primary fw-bold">Login Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Signup End -->

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