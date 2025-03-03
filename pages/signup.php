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
                <div class="col-lg-6">
                    <div class="card login-card">
                        <div class="card-body">
                            <div class="text-center pb-4">
                                <h4 class="text-primary">Sign Up</h4>
                                <h2 class="mb-3">Join Us Today!</h2>
                                <p class="mb-4">Create your employee account to access company resources and manage your
                                    profile.</p>
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
                            <form action="includes/scripts/signup.php" method="POST">
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="first_name"
                                                name="first_name" required placeholder="First Name">
                                            <label for="first_name">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="last_name"
                                                name="last_name" required placeholder="Last Name">
                                            <label for="last_name">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="email" class="form-control border-0" id="email" name="email"
                                                required placeholder="Email">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="username" class="form-control border-0" id="username" name="username"
                                                required placeholder="Username">
                                            <label for="username">Username</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control border-0" id="password"
                                                name="password" minlength="6" placeholder="Password">
                                            <label for="password">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="phone" name="phone"
                                                required placeholder="Phone Number">
                                            <label for="phone">Phone Number</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="position"
                                                name="position" required placeholder="Position">
                                            <label for="position">Position</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" class="form-control border-0" id="address" name="address"
                                                required placeholder="Address">
                                            <label for="address">Address</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="date" class="form-control border-0" id="birthday"
                                                name="birthday" required>
                                            <label for="birthday">Birthday</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100 py-3" type="submit">Sign Up</button>
                                    </div>
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