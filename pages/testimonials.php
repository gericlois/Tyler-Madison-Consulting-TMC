<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include "includes/head.php";
include "includes/connection.php";

?>

<body>

    <?php include "includes/spinner.php" ?>
    <?php include "includes/navbar.php" ?>

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Testimonials</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                <li class="breadcrumb-item active text-primary">Testimonials</li>
            </ol>
        </div>
    </div>

    <!-- Testimonial Start -->
    <div class="container-fluid testimonial py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">What Our Clients Say</h4>
                <h1 class="display-4 mb-4">Proven Results, Trusted Partnerships</h1>
                <p class="mb-0">We take pride in helping businesses build strong, efficient teams. Here’s what our
                    clients have to say about working with Tyler Madison Consulting, LLC.
                </p>
            </div>
            <?php
            include "includes/connection.php";

            $query = "SELECT * FROM testimonials where status = 1 ORDER BY created_at DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.2s">';

                while ($row = $result->fetch_assoc()) {
                    $stars = '';
                    for ($i = 0; $i < $row['rate']; $i++) {
                        $stars .= '<i class="fas fa-star"></i>';
                    }

                    echo '
                <div class="testimonial-item bg-light rounded">
                    <div class="row g-0">
                        <div class="col-4 col-lg-4 col-xl-3">
                            <div class="h-100">
                                <img src="../includes/img/profile.png" class="img-fluid h-100 rounded"
                                    style="object-fit: cover;" alt="">
                            </div>
                        </div>
                        <div class="col-8 col-lg-8 col-xl-9">
                            <div class="d-flex flex-column my-auto text-start p-4">
                                <h4 class="text-dark mb-0">' . htmlspecialchars($row["name"]) . '</h4>
                                <p class="mb-3">' . htmlspecialchars($row["company"]) . '</p>
                                <div class="d-flex text-primary mb-3">' . $stars . '</div>
                                <p class="mb-0">“' . htmlspecialchars($row["testimonial"]) . '”</p>
                            </div>
                        </div>
                    </div>
                </div>';
                }

                echo '</div>';
            } else {
                echo '<div class="text-center fw-bold">No testimonials available at the moment.</div>';

            }
            ?>

        </div>
    </div>
    <!-- Testimonial End -->

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