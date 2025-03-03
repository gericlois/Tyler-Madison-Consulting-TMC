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
    <!-- Header End -->


    <!-- Promp Start -->
    <div class="container-fluid bg-light py-5">
        <div class="container py-5 text-center">
            <div class="row justify-content-center">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">

                    <?php
                            if (isset($_GET['success'])) {
                                if ($_GET["success"] == "AccountCreated") {
                                    echo '
                               <i class="fa fa-check text-primary display-1 text-primary mb-4" style="width: 80px; height: 80px;"></i>
                                <h1 class="display-1">Account Created!</h1>
                                <p class="mb-4">Your account has been successfully created! You can now log in.</p>
                                <a class="btn btn-primary rounded-pill py-3 px-5" href="login.php">Login Now</a>';
                                }
                                if ($_GET["success"] == "emailtaken") {
                                    echo '
                                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                        <b>Email has been taken, select another email!</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                                }
                            }
                            ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Promp End -->

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