<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include "includes/connection.php";
?>
<head>
    <meta charset="utf-8">
    <title>Industry Insights & Staffing Tips – Tyler Madison Consulting Vlogs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Meta Description -->
    <meta name="description" content="Watch our vlogs for insights on staffing strategies, career tips, industry trends, and compliance updates in pharmaceuticals, manufacturing, and automation.">

    <!-- Meta Keywords (optional but can help for niche SEO) -->
    <meta name="keywords" content="staffing solutions, IQ OQ PQ protocols, computer system validation, DeltaV, Rockwell Automation, GAMP 5, FDA compliance, 21 CFR Part 11, clean in place CIP, steam in place SIP, quality management systems QMS, automation staffing, validation engineers">

    <link rel="icon" type="image/png" href="../../includes/img/tmc.ico"> 

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Inter:slnt,wght@-10..0,100..900&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../includes/lib/animate/animate.min.css" rel="stylesheet" />
    <link href="../includes/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="../includes/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../includes/css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../includes/css/style.css" rel="stylesheet">
</head>

<body>

    <?php include "includes/spinner.php" ?>
    <?php include "includes/navbar.php" ?>

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Vlogs</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">Vlogs</li>
            </ol>
        </div>
    </div>

    <!-- Vlog Start -->
     
    <div class="container-fluid service py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Watch Now</h4>
                <h1 class="display-4 mb-4">Welcome to Our Vlog</h1>
                <p class="mb-0">Join us as we explore behind-the-scenes stories, client journeys, industry insights, and everyday adventures.</p>
            </div>
            <div class="row g-4 mb-4 justify-content-center">
                <?php

                $vlog_query = "SELECT * FROM vlogs WHERE status = 1 ORDER BY created_at DESC";
                $result = $conn->query($vlog_query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $youtubeLink = $row['link'];
                        $title = $row['title'];

                        // Extract YouTube video ID
                        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/))([^\s&]+)/', $youtubeLink, $matches);
                        $videoId = $matches[1] ?? null;

                        if ($videoId) {
                            echo "<hr>
                                    <div class='col-md-10 text-center'>
                        <h3 class='display-6 mb-4'>$title</h3>
                                        <iframe width='100%' height='500' src='https://www.youtube.com/embed/{$videoId}' 
                                            title='YouTube video player' frameborder='0' 
                                            allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' 
                                            referrerpolicy='strict-origin-when-cross-origin' allowfullscreen></iframe>
                                    </div>";
                        }
                    }
                } else {
                    echo '<div class="text-center fw-bold">No videos available at the moment.</div>';
                }

                $conn->close();
                ?>
            </div>

        </div>
    </div>
    <!-- Vlog End -->

    <!-- FAQs Start -->
    <div class="container-fluid faq-section bg-light py-5">
        <div class="container py-5">
            <div class="row g-5 align-items-center">
                <div class="col-xl-6 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="h-100">
                        <div class="mb-5">
                            <h4 class="text-primary">Some Important FAQ's</h4>
                            <h1 class="display-4 mb-0">Common Frequently Asked Questions</h1>
                        </div>
                        <div class="accordion" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button border-0" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Q: What industries do you specialize in?
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse show active"
                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body rounded">
                                        A: We provide staffing solutions across various industries, including IT,
                                        engineering, software development, cybersecurity, pharmaceuticals, and business
                                        services.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Q: Do you offer both temporary and full-time staffing solutions?
                                    </button>
                                </h2>
                                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        A: Yes! We provide temporary, project-based, contract, and direct-hire
                                        placements to match your specific business needs.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        Q: How do you ensure the right fit for my company?
                                    </button>
                                </h2>
                                <div id="collapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        A: We take the time to understand your company’s culture, goals, and
                                        requirements to match you with professionals who align with your business
                                        vision.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 wow fadeInRight" data-wow-delay="0.4s">
                    <img src="../includes/img/faq.png" class="img-fluid w-100" alt="">
                </div>
            </div>
        </div>
    </div>
    <!-- FAQs End -->

    <?php include "includes/footer.php" ?>
    <?php include "includes/copyright.php" ?>

    <a href="#" class="btn btn-primary btn-lg-square rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>

    <?php include "includes/script.php" ?>
</body>

</html>