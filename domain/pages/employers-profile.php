<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "../../pages/includes/connection.php";

// Validate employer ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>Invalid employer account.</p>";
    exit();
}

$employer_id = intval($_GET['id']); // Ensure ID is an integer

// Fetch employer details
$sql = "SELECT * FROM employers WHERE employer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Employer not found.</p>";
    exit();
}

$employer = $result->fetch_assoc(); // Now, $employer is properly set
$stmt->close();
?>

<body>

    <!-- Header -->
    <?php include "includes/header.php" ?>

    <!-- Sidebar -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Employer Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Employers</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <h2><?php echo htmlspecialchars($employer['name']); ?> <a href='employers-edit.php?id=<?php echo htmlspecialchars($employer['employer_id']); ?>' class='btn btn-sm btn-warning'>Edit</a></h2>
                            <span><?php echo nl2br(htmlspecialchars($employer['description'])); ?></span>
                            <span><strong>Location:</strong><?php echo nl2br(htmlspecialchars($employer['location'])); ?></span>
                            <div class="social-links mt-2">
                                <a href="<?php echo htmlspecialchars($employer['link_facebook']); ?>"
                                    class="facebook"><i class="bi bi-facebook"></i></a>
                                <a href="<?php echo htmlspecialchars($employer['link_instagram']); ?>"
                                    class="instagram"><i class="bi bi-instagram"></i></a>
                                <a href="<?php echo htmlspecialchars($employer['link_linkedin']); ?>"
                                    class="linkedin"><i class="bi bi-linkedin"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Jobs</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Title</th>
                                        <th>Salary</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Start Date</th>
                                        <th data-type="date" data-format="YYYY/DD/MM">Deadline</th>
                                        <th>Status</th>
                                        <th>Applicants</th> <!-- Added Applicants Column -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT jp.job_id, jp.title, jp.description, jp.location, jp.salary, jp.end_at, jp.status, 
                                                u.username AS posted_by_name, jp.posted_at, e.name as employer_name, e.employer_id,
                                                (SELECT COUNT(*) FROM jobapplications ja WHERE ja.job_id = jp.job_id) AS applicant_count
                                            FROM jobpostings jp
                                            LEFT JOIN users u ON jp.posted_by = u.user_id 
                                            LEFT JOIN employers e ON jp.employer_id = e.employer_id
                                            WHERE e.employer_id = $employer_id
                                            ORDER BY jp.job_id DESC";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            // Convert numeric status to text
                                            if ($row['status'] == "1") {
                                                $row['status'] = "Active";
                                            } elseif ($row['status'] == "2") {
                                                $row['status'] = "Inactive";
                                            }
                                    
                                            // Assign class based on status
                                            $status_class = "bg-secondary"; 
                                            if ($row['status'] == "Active") {
                                                $status_class = "bg-primary";
                                            } elseif ($row['status'] == "Inactive") {
                                                $status_class = "bg-danger";
                                            }

                                            echo "<tr>
                                            <td>{$row['job_id']}</td>
                                            <td>{$row['title']}</td>
                                            <td>$" . number_format($row['salary'], 2) . "</td>
                                            <td>{$row['posted_at']}</td>
                                            <td>{$row['end_at']}</td>
                                            <td><span class='badge $status_class'>{$row['status']}</span></td>
                                            <td>{$row['applicant_count']}</td> <!-- Display applicant count -->
                                            <td>
                                                <a href='jobs-profile.php?id={$row['job_id']}' class='btn btn-sm btn-success'>View</a>
                                                <a href='jobs-edit.php?id={$row['job_id']}' class='btn btn-sm btn-warning'>Edit</a>";
                                    
                                    if ($row['status'] == "Active") {
                                        echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=2' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to make the Job Posting Inactive?\")'>Inactive</a>";
                                    } else if ($row['status'] == "Inactive") {
                                        echo " <a href='scripts/job-update.php?id={$row['job_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to make the Job Posting Active?\")'>Active</a>";
                                    }
                                    
                                    echo "</td></tr>";
                                    
                                        }
                                    } else {
                                        echo "<tr><td colspan='10' class='text-center'>No jobs found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "includes/footer.php" ?>
    <?php include "includes/scripts.php" ?>
</body>

</html>