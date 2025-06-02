<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in and is either admin or employer
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ["admin", "employer"])) {
    header("Location: login.php");
    exit();
}

// Include common files
include "includes/head.php";
include "../../pages/includes/connection.php";
?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">

                        <!-- Jobs Card -->
                        <div class="col-xxl-12 col-md-12">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>

                                <?php
                                // Get today's active job count
                                $sql_today = "SELECT COUNT(*) AS active_jobs_today FROM jobpostings WHERE status = '1' AND DATE(posted_at) < CURDATE()";
                                $result_today = $conn->query($sql_today);
                                $row_today = $result_today->fetch_assoc();
                                $active_jobs_today = $row_today['active_jobs_today'];

                                // Get today's inactive job count
                                $sql_today = "SELECT COUNT(*) AS active_jobs_today FROM jobpostings WHERE status = '2' AND DATE(posted_at) < CURDATE()";
                                $result_today = $conn->query($sql_today);
                                $row_today = $result_today->fetch_assoc();
                                $inactive_jobs_today = $row_today['active_jobs_today'];

                                // Get yesterday's active job count
                                $sql_yesterday = "SELECT COUNT(*) AS active_jobs_yesterday FROM jobpostings WHERE status = '1' AND DATE(posted_at) < CURDATE() - INTERVAL 1 DAY";
                                $result_yesterday = $conn->query($sql_yesterday);
                                $row_yesterday = $result_yesterday->fetch_assoc();
                                $active_jobs_yesterday = $row_yesterday['active_jobs_yesterday'];

                                // Calculate increase percentage
                                if ($active_jobs_yesterday > 0) {
                                    $increase_percentage = (($active_jobs_today - $active_jobs_yesterday) / $active_jobs_yesterday) * 100;
                                } else {
                                    $increase_percentage = $active_jobs_today > 0 ? 100 : 0; // If no jobs yesterday but there are jobs today, it's 100% increase
                                }
                                ?>

                                <div class="card-body">
                                    <h5 class="card-title">Active Jobs <span>| Today</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-briefcase-fill"></i>
                                        </div>
                                        <div class="ps-3  col-xxl-6 col-md-6">
                                        <span class="text-muted small pt-2 ps-1">Active</span>
                                            <h6><?php echo $active_jobs_today; ?> jobs
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">
                                                <?php echo number_format($increase_percentage, 2); ?>%
                                            </span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                        <div class="ps-3  col-xxl-6 col-md-6">
                                        <span class="text-muted small pt-2 ps-1">Inactive</span>
                                            <h6><?php echo $inactive_jobs_today; ?> jobs
                                            </h6>
                                            <span class="text-success small pt-1 fw-bold">
                                                <?php echo number_format($increase_percentage, 2); ?>%
                                            </span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Jobs Card -->


                        <!-- Employee Card -->
                        <div class="col-xxl-12 col-md-12">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>

                                <?php

                                // Get active employees count
                                $query = "SELECT COUNT(*) AS active_employees FROM employees WHERE status = 1";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();
                                $activeEmployees = $row['active_employees'];

                                // Get inactive employees count
                                $query = "SELECT COUNT(*) AS inactive_employees FROM employees WHERE status = 2";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();
                                $inactiveEmployees = $row['inactive_employees'];

                                // Get blocked employees count
                                $query = "SELECT COUNT(*) AS blocked_employees FROM employees WHERE status = 3";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();
                                $blockedEmployees = $row['blocked_employees'];

                                // Get percentage increase
                                $queryIncrease = "SELECT 
                                    (COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) * 100.0 / 
                                    NULLIF(COUNT(CASE WHEN DATE(created_at) = CURDATE() - INTERVAL 1 DAY THEN 1 END), 0)
                                    ) AS percentage_increase
                                FROM employees";
                                $resultIncrease = $conn->query($queryIncrease);
                                $rowIncrease = $resultIncrease->fetch_assoc();
                                $percentageIncrease = $rowIncrease['percentage_increase'] ?: 0;
                                ?>

                                <div class="card-body">
                                    <h5 class="card-title">Employees <span>| Today</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people-fill"></i>
                                        </div>
                                        <div class="ps-3 col-xxl-4 col-md-4">
                                            <span class="text-muted small pt-2 ps-1">Active</span>
                                            <h6><?php echo $activeEmployees; ?></h6>
                                            <span
                                                class="text-success small pt-1 fw-bold"><?php echo number_format($percentageIncrease, 2); ?>%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                        <div class="ps-3 col-xxl-4 col-md-4">
                                            <span class="text-muted small pt-2 ps-1">Inactive</span>
                                            <h6><?php echo $inactiveEmployees; ?></h6>
                                            <span
                                                class="text-success small pt-1 fw-bold"><?php echo number_format($percentageIncrease, 2); ?>%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                        <div class="ps-3 col-xxl-4 col-md-4">
                                            <span class="text-muted small pt-2 ps-1">Blocked</span>
                                            <h6><?php echo $blockedEmployees; ?></h6>
                                            <span
                                                class="text-success small pt-1 fw-bold"><?php echo number_format($percentageIncrease, 2); ?>%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Employee Card -->


                        <!-- Employers Card -->
                        <div class="col-xxl-12 col-xl-12">

                            <div class="card info-card customers-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>

                                <?php

                                // Get active employers count
                                $query = "SELECT COUNT(DISTINCT employer_id) AS active_employers FROM jobpostings WHERE status = '1'";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();
                                $activeEmployers = $row['active_employers'];

                                // Get inactive employers count
                                $query = "SELECT COUNT(DISTINCT employer_id) AS inactive_employers FROM jobpostings WHERE status = '2'";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();
                                $inactiveEmployers = $row['inactive_employers'];

                                // Get blocked employers count
                                $query = "SELECT COUNT(DISTINCT employer_id) AS blocked_employers FROM jobpostings WHERE status = '3'";
                                $result = $conn->query($query);
                                $row = $result->fetch_assoc();
                                $blockedEmployers = $row['blocked_employers'];


                                // Get percentage increase
                                $queryIncrease = "SELECT 
                                    (COUNT(DISTINCT CASE WHEN DATE(posted_at) = CURDATE() THEN employer_id END) * 100.0 / 
                                    NULLIF(COUNT(DISTINCT CASE WHEN DATE(posted_at) = CURDATE() - INTERVAL 1 DAY THEN employer_id END), 0)
                                    ) AS percentage_increase
                                FROM jobpostings";
                                $resultIncrease = $conn->query($queryIncrease);
                                $rowIncrease = $resultIncrease->fetch_assoc();
                                $percentageIncrease = $rowIncrease['percentage_increase'] ?: 0;
                                ?>

                                <div class="card-body">
                                    <h5 class="card-title">Employers <span>| Today</span></h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div class="ps-3 col-xxl-4 col-xl-4">
                                            <span class="text-muted small pt-2 ps-1">Active</span>
                                            <h6><?php echo $activeEmployers; ?></h6>
                                            <span
                                                class="text-success small pt-1 fw-bold"><?php echo number_format($percentageIncrease, 2); ?>%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                        <div class="ps-3 col-xxl-4 col-xl-4">
                                            <span class="text-muted small pt-2 ps-1">Inactive</span>
                                            <h6><?php echo $inactiveEmployers; ?></h6>
                                            <span
                                                class="text-success small pt-1 fw-bold"><?php echo number_format($percentageIncrease, 2); ?>%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>
                                        <div class="ps-3 col-xxl-4 col-xl-4">
                                            <span class="text-muted small pt-2 ps-1">Blocked</span>
                                            <h6><?php echo $blockedEmployers; ?></h6>
                                            <span
                                                class="text-success small pt-1 fw-bold"><?php echo number_format($percentageIncrease, 2); ?>%</span>
                                            <span class="text-muted small pt-2 ps-1">increase</span>
                                        </div>

                                    </div>
                                </div>

                            </div>

                        </div><!-- End Employers Card -->




                        <div class="col-lg-12">
                            <?php
                            $progressCounts = array_fill(0, 7, 0); // Indexes 0 to 6 for each progress state
                            
                            $stmt = $conn->prepare("SELECT progress, COUNT(*) as count FROM jobapplications GROUP BY progress");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while ($row = $result->fetch_assoc()) {
                                $progress = intval($row['progress']);
                                if ($progress >= 0 && $progress <= 6) {
                                    $progressCounts[$progress] = intval($row['count']);
                                }
                            }

                            $stmt->close();
                            ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Job Applications by Stage</h5>

                                    <!-- Polar Area Chart -->
                                    <canvas id="polarAreaChart" style="max-height: 400px;"></canvas>
                                    <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new Chart(document.querySelector('#polarAreaChart'), {
                                            type: 'polarArea',
                                            data: {
                                                labels: [
                                                    'Applied',
                                                    'First Interview',
                                                    'Second Interview',
                                                    'Final Interview',
                                                    'See HR',
                                                    'Declined',
                                                    'Hired'
                                                ],
                                                datasets: [{
                                                    label: 'Job Applications by Progress',
                                                    data: <?= json_encode($progressCounts); ?>,
                                                    backgroundColor: [
                                                        'rgba(255, 99, 132, 0.6)',
                                                        'rgba(255, 159, 64, 0.6)',
                                                        'rgba(255, 205, 86, 0.6)',
                                                        'rgba(75, 192, 192, 0.6)',
                                                        'rgba(54, 162, 235, 0.6)',
                                                        'rgba(153, 102, 255, 0.6)',
                                                        'rgba(201, 203, 207, 0.6)'
                                                    ]
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                plugins: {
                                                    legend: {
                                                        position: 'right'
                                                    }
                                                }
                                            }
                                        });
                                    });
                                    </script>

                                    <!-- End Polar Area Chart -->

                                </div>
                            </div>
                        </div>

                        <!-- Recent Application -->
                        <?php
                        $query = "SELECT ja.jobapplication_id, 
                                u.first_name, u.last_name, 
                                jp.title AS job_title, 
                                ja.created_at, ja.status
                        FROM jobapplications ja
                        JOIN jobpostings jp ON ja.job_id = jp.job_id
                        JOIN employees e ON ja.employee_id = e.user_id
                        JOIN users u ON e.user_id = u.user_id
                        ORDER BY ja.created_at DESC
                        LIMIT 5";

                        $result = mysqli_query($conn, $query);
                        ?>

                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
                                <div class="card-body">
                                    <h5 class="card-title">Recent Job Applications <span>| Today</span></h5>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Applicant</th>
                                                <th scope="col">Job Title</th>
                                                <th scope="col">Applied Date</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = mysqli_fetch_assoc($result)) {
                                                // Convert status values
                                                $status_text = ($row['status'] == "1") ? "Active" : "Inactive";

                                                // Assign class based on status
                                                $status_class = ($row['status'] == "1") ? "bg-primary" : "bg-danger";
                                                ?>
                                            <tr>
                                                <th scope="row"><a
                                                        href="#">#<?php echo $row['jobapplication_id']; ?></a></th>
                                                <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                <td><a href="#"
                                                        class="text-primary"><?php echo $row['job_title']; ?></a></td>
                                                <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
                                                <td>
                                                    <span class="badge <?php echo $status_class; ?>">
                                                        <?php echo $status_text; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <!-- End Recent Application -->

                        <!-- Jobs Reports -->
                        <div class="col-12">
                            <?php
                            // Query to count jobs by month and status
                            $query = "SELECT 
                            DATE_FORMAT(posted_at, '%Y-%m') AS job_month,
                            SUM(CASE WHEN status = '1' THEN 1 ELSE 0 END) AS active_jobs,
                            SUM(CASE WHEN status = '0' THEN 1 ELSE 0 END) AS inactive_jobs,
                            SUM(CASE WHEN status = '2' THEN 1 ELSE 0 END) AS pending_jobs
                        FROM jobpostings
                        GROUP BY job_month
                        ORDER BY job_month ASC";

                            $result = $conn->query($query);

                            $months = [];
                            $activeJobs = [];
                            $inactiveJobs = [];
                            $pendingJobs = [];

                            while ($row = $result->fetch_assoc()) {
                                $months[] = $row['job_month'];
                                $activeJobs[] = $row['active_jobs'];
                                $inactiveJobs[] = $row['inactive_jobs'];
                                $pendingJobs[] = $row['pending_jobs'];
                            }
                            ?>
                            <div class="card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="#">Today</a></li>
                                        <li><a class="dropdown-item" href="#">This Month</a></li>
                                        <li><a class="dropdown-item" href="#">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Reports <span>/Today</span></h5>

                                    <!-- Line Chart -->
                                    <div id="reportsChart"></div>

                                    <script>
                                    document.addEventListener("DOMContentLoaded", () => {
                                        new ApexCharts(document.querySelector("#reportsChart"), {
                                            series: [{
                                                name: 'Active Jobs',
                                                data: <?php echo json_encode($activeJobs); ?>
                                            }, {
                                                name: 'Inactive Jobs',
                                                data: <?php echo json_encode($inactiveJobs); ?>
                                            }, {
                                                name: 'Pending Jobs',
                                                data: <?php echo json_encode($pendingJobs); ?>
                                            }],
                                            chart: {
                                                height: 350,
                                                type: 'area',
                                                toolbar: {
                                                    show: false
                                                },
                                            },
                                            markers: {
                                                size: 4
                                            },
                                            colors: ['#4154f1', '#ff771d', '#2eca6a'],
                                            fill: {
                                                type: "gradient",
                                                gradient: {
                                                    shadeIntensity: 1,
                                                    opacityFrom: 0.3,
                                                    opacityTo: 0.4,
                                                    stops: [0, 90, 100]
                                                }
                                            },
                                            dataLabels: {
                                                enabled: false
                                            },
                                            stroke: {
                                                curve: 'smooth',
                                                width: 2
                                            },
                                            xaxis: {
                                                categories: <?php echo json_encode($months); ?>,
                                                labels: {
                                                    format: 'MMM yyyy' // Format for month-year display
                                                }
                                            },
                                            tooltip: {
                                                x: {
                                                    format: 'MMM yyyy'
                                                },
                                            }
                                        }).render();
                                    });
                                    </script>

                                    <!-- End Line Chart -->

                                </div>

                            </div>
                        </div><!-- End Jobs Reports -->

                    </div>
                </div><!-- End Left side columns -->



                <!-- Right side columns -->
                <div class="col-lg-4">

                    <!-- Top Employeer -->
                    <div class="col-12">
                        <div class="card top-selling overflow-auto">
                            <div class="filter">
                                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <li class="dropdown-header text-start">
                                        <h6>Filter</h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#">Today</a></li>
                                    <li><a class="dropdown-item" href="#">This Month</a></li>
                                    <li><a class="dropdown-item" href="#">This Year</a></li>
                                </ul>
                            </div>

                            <?php
                            // Query to get the employer with the most job postings, including email and phone
                            $query = "SELECT e.employer_id, e.name, e.profile_picture, e.email, e.phone, COUNT(jp.job_id) AS total_jobs
                                FROM employers e
                                JOIN jobpostings jp ON e.employer_id = jp.employer_id
                                GROUP BY e.employer_id, e.name, e.profile_picture, e.email, e.phone
                                ORDER BY total_jobs DESC
                                LIMIT 1";

                            $result = mysqli_query($conn, $query);
                            $top_employer = mysqli_fetch_assoc($result);
                            ?>

                            <div class="card-body pb-0">
                                <h5 class="card-title">Top Employer <span>| Most Job Posts</span></h5>

                                <table class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th scope="col">Employer</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Total Jobs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($top_employer): ?>
                                        <tr>
                                            <td><a href="employers-profile.php?id=<?= htmlspecialchars($top_employer['employer_id']) ?>"
                                                    class="text-primary fw-bold"><?= htmlspecialchars($top_employer['name']) ?></a>
                                            </td>
                                            <td><?= htmlspecialchars($top_employer['email']) ?></td>
                                            <td class="fw-bold"><?= $top_employer['total_jobs'] ?></td>
                                        </tr>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No job postings available</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Top Employeer -->


                    <!-- Recent Hired Jobs -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>
                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">Newly Hired <span>| as of Today</span></h5>

                            <div class="card-body">
                                <?php
                                $stmt = $conn->prepare("
                                    SELECT 
                                        u.first_name, u.last_name,
                                        j.title,
                                        e.employee_id
                                    FROM jobapplications ja
                                    JOIN employees e ON ja.employee_id = e.employee_id
                                    JOIN users u ON e.user_id = u.user_id
                                    JOIN jobpostings j ON ja.job_id = j.job_id
                                    WHERE ja.progress = 6
                                    ORDER BY ja.created_at DESC
                                    LIMIT 5
                                ");
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0):
                                    while ($row = $result->fetch_assoc()):
                                        $name = htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                                        $title = htmlspecialchars($row['title']);
                                        $employee_id = (int) $row['employee_id'];
                                        ?>
                                <div class="post-item clearfix mb-3">
                                    <p class="mb-0">
                                        <b><a href="employees-profile.php?id=<?= $employee_id ?>"><?= $name ?></a></b>
                                    </p>
                                    <p class="mb-0">Hired for: <strong><?= $title ?></strong></p>
                                </div>
                                <?php
                                    endwhile;
                                else:
                                    echo "<p class='text-muted'>No hires yet.</p>";
                                endif;

                                $stmt->close();
                                ?>
                            </div><!-- End sidebar recent posts -->
                        </div>
                    </div><!-- End Recent Hired Jobs -->


                    <!-- News & Updates Traffic -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="#">Today</a></li>
                                <li><a class="dropdown-item" href="#">This Month</a></li>
                                <li><a class="dropdown-item" href="#">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body pb-0">
                            <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

                            <div class="news">
                                <div class="post-item clearfix">
                                    <img src="../assets/img/news-1.jpg" alt="">
                                    <h4><a href="#">Feature Still on Progress</a></h4>
                                    <p>Coming soon...</p>
                                </div>

                                <div class="post-item clearfix">
                                    <img src="../assets/img/news-2.jpg" alt="">
                                    <h4><a href="#">Feature Still on Progress</a></h4>
                                    <p>Coming soon...</p>
                                </div>
                                <br>

                            </div><!-- End sidebar recent posts-->

                        </div>
                    </div><!-- End News & Updates -->



                </div><!-- End Right side columns -->

            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>