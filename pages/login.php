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


    <!-- Login Start -->
    <div class="container-fluid bg-light py-5 d-flex justify-content-center align-items-center"
        style="min-height: 10vh;">
        <div class="container d-flex justify-content-center">
            <div class="card login-card wow fadeInUp" data-wow-delay="0.2s">
                <div class="card-body p-5 text-center">
                    <h4 class="text-primary">Login</h4>
                    <h1 class="display-6 mb-4">Your journey starts here.</h1>
                    <p class="text-muted">Access your personalized dashboard now</p>

                    <?php
                            if (isset($_GET['error'])) {
                                if ($_GET["error"] == "Accountnotfound") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>There is no account registered! First, create an account! <a class="text-primary fw-bold" href="signup.php">SIGN UP NOW!</a></b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                                if ($_GET["error"] == "IncorrectPassword") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>The password is incorrect. Before logging in, make sure your password is correct.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
                    <hr>
                    <form action="includes/scripts/login.php" method="POST">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control border-0" id="username" name="username"
                                placeholder="Username" required>
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control border-0" id="password" name="password"
                                placeholder="Password" required>
                            <label for="password">Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3">Login</button>
                    </form>


                    <p class="mt-4"> Don't have an account yet? <a class="text-primary fw-bold" href="signup.php">SIGN
                            UP NOW!</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact End -->

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