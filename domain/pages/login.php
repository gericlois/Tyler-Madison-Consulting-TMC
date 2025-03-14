<!DOCTYPE html>
<html lang="en">
<?php

session_start();
  include "includes/head.php";
  include "../../pages/includes/connection.php";
  
?>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <img src="../assets/img/TMClogo2.png" alt="TMC Logo"
                                    style="width: 500px; height: auto;">
                            </div><!-- End Logo -->

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Login to Your Admin Account</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>

                                    <?php if (isset($_GET['error'])): ?>
                                    <div class="alert alert-danger">
                                        <?php
                                        switch ($_GET['error']) {
                                            case 'empty_fields':
                                                echo "Please fill in all fields.";
                                                break;
                                            case 'invalid_password':
                                                echo "Incorrect password. Please try again.";
                                                break;
                                            case 'admin_not_found':
                                                echo "Admin user not found. Please check your credentials.";
                                                break;
                                            default:
                                                echo "An unknown error occurred.";
                                                break;
                                        }
                                        ?>
                                    </div>
                                    <?php endif; ?>

                                    <form class="row g-3 needs-validation" action="scripts/login.php" method="POST">

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Username</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="text" name="username" class="form-control" id="username"
                                                    required>
                                                <div class="invalid-feedback">Please enter your username.</div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="yourPassword" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control" id="password"
                                                required>
                                            <div class="invalid-feedback">Please enter your password!</div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Login</button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                            <div class="credits">
                                Designed by <a href="https://casugayportfolio.my.canva.site/" target="_blank"
                                    rel="noopener noreferrer">GLCasugay</a>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>