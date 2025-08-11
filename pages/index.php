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


    <!-- Carousel Start -->
    <div class="header-carousel owl-carousel">
        <div class="header-carousel-item bg-primary">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row g-4 align-items-center">
                        <div class="col-lg-7 animated fadeInLeft">
                            <div class="text-sm-center text-md-start">
                                <h5 class="text-white text-uppercase fw-bold mb-4">Welcome To Tyler Madison Consulting.
                                    LLC</h5>
                                <h1 class="display-3 text-white mb-4">Your Trusted Partner in Technical Staffing
                                    Solutions</h1>
                                <p class="mb-5 fs-5">Providing expert staffing solutions tailored to the pharmaceutical
                                    industry needs
                                </p>
                                <div class="d-flex justify-content-center justify-content-md-start flex-shrink-0 mb-4">
                                    <a class="btn btn-light rounded-pill py-3 px-4 px-md-5 me-2" href="vlog.php"><i
                                            class="fas fa-play-circle me-2"></i> Watch Video</a>
                                    <a class="btn btn-dark rounded-pill py-3 px-4 px-md-5 ms-2" href="#aboutus">Learn More</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 animated fadeInRight">
                            <div class="calrousel-img" style="object-fit: cover;">
                                <img src="../includes/../includes/img/carousel1.png" class="img-fluid w-100" alt="Tyler Madison Consulting delivering specialized staffing solutions for life sciences, manufacturing, and automation industries">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-carousel-item bg-primary">
            <div class="carousel-caption">
                <div class="container">
                    <div class="row gy-4 gy-lg-0 gx-0 gx-lg-5 align-items-center">
                        <div class="col-lg-5 animated fadeInLeft">
                            <div class="calrousel-img">
                                <img src="../includes/img/carousel2.png" class="img-fluid w-100" alt="Tyler Madison Consulting delivering specialized staffing solutions for life sciences, manufacturing, and automation industries">
                            </div>
                        </div>
                        <div class="col-lg-7 animated fadeInRight">
                            <div class="text-sm-center text-md-end">
                                <h5 class="text-white text-uppercase fw-bold mb-4">Welcome To Tyler Madison Consulting.
                                    LLC</h5>
                                <h1 class="display-3 text-white mb-4">Your Reliable Partner in Technical Staffing
                                    Excellence</h1>
                                <p class="mb-5 fs-5">Delivering specialized staffing solutions designed to meet the
                                    unique needs of the pharmaceutical industry.
                                </p>
                                <div class="d-flex justify-content-center justify-content-md-end flex-shrink-0 mb-4">
                                    <a class="btn btn-light rounded-pill py-3 px-4 px-md-5 me-2" href="vlog.php"><i
                                            class="fas fa-play-circle me-2"></i> Watch Video</a>
                                    <a class="btn btn-dark rounded-pill py-3 px-4 px-md-5 ms-2" href="#aboutus">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Carousel End -->

    <!-- About Start -->
    <div class="container-fluid bg-light about py-5" id="aboutus">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">About Us</h4>
                <h1 class="display-4 mb-4">Empowering Businesses with Expert Technical Talent</h1>
                <p class="mb-0">At Tyler Madison Consulting, LLC, we bridge the gap between businesses and top-tier
                    professionals, delivering tailored staffing solutions that drive innovation, efficiency, and
                    success.
                </p>
            </div>
            <div class="row g-5">
                <div class="col-xl-7 wow fadeInLeft" data-wow-delay="0.2s">
                    <div class="about-item-content bg-white rounded p-5 h-100">
                        <h4 class="text-primary">About Our Company</h4>
                        <h3 class="display-6 mb-4">Connecting Talent with Opportunity</h3>
                        <p>At Tyler Madison Consulting, LLC, we provide specialized technical consulting and staffing solutions designed to meet the unique workforce needs of each client. With a passion for delivering high-quality, results-driven hiring services, our mission is to connect life sciences, manufacturing, and automation companies with top-tier professionals who drive innovation, efficiency, and business growth.
                        </p>
                        <p>Unlike traditional staffing firms, we go beyond filling positions—we help you build a skilled, efficient, and future-ready workforce that delivers measurable success.
                        </p>
                        <hr>
                        <p class="text-dark"><i class="fa fa-check text-primary me-3"></i>We Connect You with Top Talent –<small>Access highly qualified candidates in validation, automation, engineering, and quality compliance.</small> 
                        </p>
                        <p class="text-dark"><i class="fa fa-check text-primary me-3"></i>We Help You Scale Efficiently – <small>Flexible staffing models to match your project timelines and business demands.</small>
                        </p>
                        <p class="text-dark mb-4"><i class="fa fa-check text-primary me-3"></i>We Streamline Your Hiring Process – <small>Proven recruitment strategies that reduce time-to-hire while ensuring regulatory compliance.</small>
                        </p>
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="#">We Ensure the Perfect Fit </a>
                    </div>
                </div>
                <div class="col-xl-5 wow fadeInRight" data-wow-delay="0.2s">
                    <div class="bg-white rounded p-5 h-100">
                        <div class="row g-4 justify-content-center">
                            <div class="col-12">
                                <div class="rounded bg-light">
                                    <img src="../includes/img/about-1.png" class="img-fluid rounded w-100" alt="Professional staffing consultant meeting with client to discuss FDA compliance and validation needs">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="counter-item bg-light rounded p-3 h-100">
                                    <div class="counter-counting">
                                        <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">129</span>
                                        <span class="h1 fw-bold text-primary">+</span>
                                    </div>
                                    <h4 class="mb-0 text-dark">Staff Hired</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="counter-item bg-light rounded p-3 h-100">
                                    <div class="counter-counting">
                                        <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">99</span>
                                        <span class="h1 fw-bold text-primary">+</span>
                                    </div>
                                    <h4 class="mb-0 text-dark">Awards WON</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="counter-item bg-light rounded p-3 h-100">
                                    <div class="counter-counting">
                                        <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">556</span>
                                        <span class="h1 fw-bold text-primary">+</span>
                                    </div>
                                    <h4 class="mb-0 text-dark">Skilled Agents</h4>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="counter-item bg-light rounded p-3 h-100">
                                    <div class="counter-counting">
                                        <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">967</span>
                                        <span class="h1 fw-bold text-primary">+</span>
                                    </div>
                                    <h4 class="mb-0 text-dark">Team Members</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->

    <!-- Feature Start -->
    <div class="container-fluid feature bg-light">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Our Features</h4>
                <h1 class="display-4 mb-4">Why Partner with Tyler Madison Consulting?</h1>
                <p class="mb-0">We go beyond staffing — we deliver strategic workforce solutions that drive business growth, optimize operations, and ensure compliance with industry standards.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="feature-item p-4 pt-0">
                        <div class="feature-icon p-4 mb-4">
                            <i class="fa fa-heartbeat fa-3x"></i>
                        </div>
                        <h4 class="mb-4"> Tailored Staffing Solutions </h4>
                        <p class="mb-4">Whether you require temporary, project-based, or permanent hires, we provide the right professionals for your operational and regulatory needs. From validation engineers to automation specialists, our staffing solutions are designed to meet specific compliance and technical requirements.
                        </p>
                        <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="feature-item p-4 pt-0">
                        <div class="feature-icon p-4 mb-4">
                            <i class="fa fa-plus-square fa-3x"></i>
                        </div>
                        <h4 class="mb-4"> Industry-Specific Expertise</h4>
                        <p class="mb-4">Our consultants bring proven experience in IT, engineering, pharmaceuticals, life sciences, manufacturing, and compliance-driven sectors. We understand the demands of FDA regulations, data integrity requirements, GAMP 5 methodologies, and 21 CFR Part 11 standards, ensuring we source the best talent for your industry.
                        </p>
                        <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="feature-item p-4 pt-0">
                        <div class="feature-icon p-4 mb-4">
                            <i class="fa fa-stethoscope fa-3x"></i>
                        </div>
                        <h4 class="mb-4">Efficient & Cost-Effective Hiring </h4>
                        <p class="mb-4">Through our streamlined recruitment process, we save clients time and money while delivering highly qualified professionals — from control system engineers to quality assurance managers — who are ready to contribute immediately to your projects and operations.
                        </p>
                        <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.8s">
                    <div class="feature-item p-4 pt-0">
                        <div class="feature-icon p-4 mb-4">
                            <i class="fa fa-medkit fa-3x"></i>
                        </div>
                        <h4 class="mb-4">Seamless Talent Integration</h4>
                        <p class="mb-4">We ensure every placement is a perfect fit for your culture, technical needs, and compliance goals. Whether building a specialized project team or enhancing your existing workforce, we focus on long-term retention and measurable success.
                        </p>
                        <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Feature End -->

    <!-- Service Start -->
    <div class="container-fluid service py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Our Services</h4>
                <h1 class="display-4 mb-4">Expert Staffing & Workforce Solutions for Specialized Industries</h1>
                <p class="mb-0">At Tyler Madison Consulting, LLC, we provide specialized staffing solutions to help businesses in life sciences, pharmaceuticals, manufacturing, automation, and technology find highly skilled professionals for critical roles. Whether you need short-term expertise, long-term consulting, or customized workforce strategies, we ensure you get the right talent to meet your operational, technical, and compliance requirements.
                </p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.2s">
                    <div class="service-item">
                        <div class="service-img">
                            <img src="../includes/img/1.png" class="img-fluid rounded-top w-100" alt="Validation engineer reviewing IQ, OQ, and PQ protocols for manufacturing compliance">
                            <div class="service-icon p-3">
                                <i class="fa fa-users fa-2x"></i>
                            </div>
                        </div>
                        <div class="service-content p-4">
                            <div class="service-content-inner">
                                <a href="#" class="d-inline-block h4 mb-4">Temporary & Project-Based Staffing</a>
                                <p class="mb-4">When your business needs skilled experts for short-term projects or urgent assignments, we deliver specialized temporary staffing to fill crucial roles and maintain productivity. Ideal for Installation Qualification (IQ), Operational Qualification (OQ), and Performance Qualification (PQ) projects, as well as computer system validation and regulatory compliance tasks.</p>
                                <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.4s">
                    <div class="service-item">
                        <div class="service-img">
                            <img src="../includes/img/2.png" class="img-fluid rounded-top w-100" alt="Automation specialist integrating Rockwell Automation systems in a manufacturing facility">
                            <div class="service-icon p-3">
                                <i class="fa fa-hospital fa-2x"></i>
                            </div>
                        </div>
                        <div class="service-content p-4">
                            <div class="service-content-inner">
                                <a href="#" class="d-inline-block h4 mb-4">Long-Term Consulting & Contract Staffing</a>
                                <p class="mb-4">For organizations seeking dedicated professionals on a contract basis, we provide consultants with expertise in automation systems, integrated control systems (DeltaV, Rockwell Automation), GAMP 5 methodologies, and 21 CFR Part 11 compliance. Our specialists integrate seamlessly into your team, helping you achieve business goals while maintaining FDA data integrity requirements.

                                </p>
                                <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.6s">
                    <div class="service-item">
                        <div class="service-img">
                            <img src="../includes/img/3.png" class="img-fluid rounded-top w-100" alt="Recruitment specialist interviewing candidate for quality management system (QMS) position">
                            <div class="service-icon p-3">
                                <i class="fa fa-user-md fa-2x"></i>
                            </div>
                        </div>
                        <div class="service-content p-4">
                            <div class="service-content-inner">
                                <a href="#" class="d-inline-block h4 mb-4">Direct Hire & Permanent Placement</a>
                                <p class="mb-4">Finding the right full-time employee with technical and regulatory expertise can be challenging. Our direct hire recruitment connects you with top-tier candidates skilled in Quality Management Systems (QMS), validation efforts, and manufacturing optimization — ensuring both a cultural fit and long-term success.</p>
                                <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3 wow fadeInUp" data-wow-delay="0.8s">
                    <div class="service-item">
                        <div class="service-img">
                            <img src="../includes/img/4.png" class="img-fluid rounded-top w-100" alt="Engineering team performing clean-in-place (CIP) and steam-in-place (SIP) operations in a production plant">
                            <div class="service-icon p-3">
                                <i class="fa fa-heart fa-2x"></i>
                            </div>
                        </div>
                        <div class="service-content p-4">
                            <div class="service-content-inner">
                                <a href="#" class="d-inline-block h4 mb-4">Custom Workforce Solutions</a>
                                <p class="mb-4">We understand that every business has unique operational needs. That’s why we design flexible, scalable staffing strategies for specialized projects — from clean-in-place (CIP) and steam-in-place (SIP) operations to system documentation development and protocol execution. Our solutions align staffing capabilities with your objectives for maximum efficiency.
                                </p>
                                <a class="btn btn-primary rounded-pill py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.2s">
                    <a href="#" class="d-inline-block h4 mb-4"> Let’s find the right talent for your business. Contact
                        Us today!</a><br>
                    <a class="btn btn-primary rounded-pill py-3 px-5" href="#">More Services</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->

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
                    <img src="../includes/img/faq.png" class="img-fluid w-100" alt="Consulting team designing custom staffing strategies for specialized industries">
                </div>
            </div>
        </div>
    </div>
    <!-- FAQs End -->

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