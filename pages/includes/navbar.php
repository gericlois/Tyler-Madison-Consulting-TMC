<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get current file name
?>
<!-- Topbar Start -->
<div class="container-fluid topbar px-0 px-lg-4 bg-light py-2 d-none d-lg-block">
    <div class="container">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-8 text-center text-lg-start mb-lg-0">
                <div class="d-flex flex-wrap">
                    <div class="border-end border-primary pe-3">
                        <a href="#" class="text-muted small"><i class="fas fa-map-marker-alt text-primary me-2"></i>Find
                            A Location</a>
                    </div>
                    <div class="ps-3">
                        <a href="mailto:example@gmail.com" class="text-muted small"><i
                                class="fas fa-envelope text-primary me-2"></i>example@gmail.com</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-flex justify-content-end">
                    <div class="d-flex border-end border-primary pe-3">
                        <a class="btn p-0 text-primary me-3" href="../domain/pages/index.php"><i class="fab fa-android"></i></a>
                        <a class="btn p-0 text-primary me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="btn p-0 text-primary me-3" href="#"><i class="fab fa-instagram"></i></a>
                        <a class="btn p-0 text-primary me-0" href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                    <div class="dropdown ms-3">
                        <a href="#" class="dropdown-toggle text-dark" data-bs-toggle="dropdown"><small><i
                                    class="fas fa-globe-europe text-primary me-2"></i> English</small></a>
                        <div class="dropdown-menu rounded">
                            <a href="#" class="dropdown-item">English</a>
                            <a href="#" class="dropdown-item">Bangla</a>
                            <a href="#" class="dropdown-item">French</a>
                            <a href="#" class="dropdown-item">Spanish</a>
                            <a href="#" class="dropdown-item">Arabic</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Topbar End -->

<div class="container-fluid nav-bar px-0 px-lg-4 py-lg-0">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a href="index.php" class="navbar-brand p-0">
                <img src="../includes/img/TMClogo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav mx-0 mx-lg-auto">
                    <a href="index.php"
                        class="nav-item nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a>
                    <a href="about.php"
                        class="nav-item nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a>
                    <a href="jobs.php"
                        class="nav-item nav-link <?php echo ($current_page == 'jobs.php') ? 'active' : ''; ?>">Jobs</a>
                    <a href="contact.php"
                        class="nav-item nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact</a>

                    <div class="nav-btn px-3">
                        <button class="btn-search btn btn-primary btn-md-square rounded-circle flex-shrink-0"
                            data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search"></i></button>

                        <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php"
                            class="btn btn-primary rounded-pill py-2 px-4 ms-3 flex-shrink-0">Profile</a>
                        <a href="includes/scripts/logout.php"
                            class="btn btn-danger rounded-pill py-2 px-4 ms-3 flex-shrink-0">Logout</a>
                        <?php else: ?>
                        <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4 ms-3 flex-shrink-0">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="d-none d-xl-flex flex-shrink-0 ps-4">
                <a href="#" class="btn btn-light btn-lg-square rounded-circle position-relative wow tada"
                    data-wow-delay=".9s">
                    <i class="fa fa-user fa-2x"></i>
                </a>
                <div class="d-flex flex-column ms-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <span class="text-dark"><?php echo htmlspecialchars($_SESSION['position']); ?></span>
                    <?php else: ?>
                    <span>Guest</span>
                    <span class="text-dark">Visitor</span>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </div>
</div>