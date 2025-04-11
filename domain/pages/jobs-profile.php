<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) { // Ensure session is active
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "../../pages/includes/connection.php";

// Check if job ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid job listing.");
}

$job_id = intval($_GET['id']); // Sanitize input

// Fetch job details
$stmt = $conn->prepare("SELECT * FROM jobpostings j left join users u ON j.posted_by= u.user_id WHERE j.job_id = ?");
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Job not found.");
}

$job = $result->fetch_assoc(); // Now, $job is properly set
$stmt->close();

?>


<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Job Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="jobs.php">Jobs</a></li>
                    <li class="breadcrumb-item active">Job Profile</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">

                <div class="col-xl-12">

                    <div class="card">
                        <div class="card-body pt-3">

                            <h2><?php echo htmlspecialchars($job['title']); ?>
                                <a href='jobs-edit.php?id=<?= $job_id ?>' class='btn btn-success rounded-pill'>
                                    <i class="bi bi-plus-circle me-1"></i>Edit
                                </a>
                            </h2>
                            <p>
                                <?php
                                if ($job['status'] == "Active") {
                                    echo ' <span class="badge bg-primary"><i class="bi bi-check-circle me-1"></i> Active</span>';
                                } else if ($job['status'] == "Inactive") {
                                    echo ' <span class="badge bg-primary"><i class="bi bi-exclamation-octagon me-1"></i> Inactive</span>';
                                }
                                ?>
                            </p>

                            <h5 class="card-title">Description:</h5>
                            <p class="small"><?php echo htmlspecialchars($job['description']); ?></p>

                            <h5 class="card-title">Job Posting Details</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label "><b>Job Type:</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['job_type']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Salary:</b></div>
                                <div class="col-lg-9 col-md-8"> $<?php echo htmlspecialchars($job['salary']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Schedule:</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['schedule']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Location:</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['location']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Skills:</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['skills']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Date Posted:</b></div>
                                <div class="col-lg-9 col-md-8">
                                    <?php echo htmlspecialchars($job['posted_at']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Deadline:</b></div>
                                <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($job['end_at']); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label"><b>Posted By:</b></div>
                                <div class="col-lg-9 col-md-8">
                                    <?php echo htmlspecialchars($job['first_name']); ?>
                                    <?php echo htmlspecialchars($job['last_name']); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>


                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body pt-3">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Employee Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Applied Date</th>
                                        <th>Progress</th>
                                        <th>Comments</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT ja.status as jobstatus, ja.jobapplication_id AS application_id, ja.created_at AS applied_at, ja.employee_id AS user_id, u.first_name, u.last_name, u.email, u.phone, ja.progress, e.employee_id AS employee_id FROM jobapplications ja 
                                    INNER JOIN employees e ON e.employee_id = ja.employee_id 
                                    INNER JOIN users u ON u.user_id = e.user_id 
                                    WHERE ja.job_id = $job_id and ja.status = 1 ORDER BY ja.created_at DESC;";

                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $progress_options = [
                                                0 => "Applied",
                                                1 => "First Interview",
                                                2 => "Second Interview",
                                                3 => "Final Interview",
                                                4 => "See HR",
                                                5 => "Declined",
                                            ];

                                            $comment_sql = "SELECT comment, created_at FROM comments WHERE job_id = ? AND employee_id  = ?";
                                            $comment_stmt = $conn->prepare($comment_sql);
                                            $comment_stmt->bind_param("ii", $job_id, $row['employee_id']);
                                            $comment_stmt->execute();
                                            $comment_result = $comment_stmt->get_result();
                                            $comments = [];
                                            while ($comment_row = $comment_result->fetch_assoc()) {
                                                $comments[] = $comment_row; // Store each comment and its date
                                            }
                                            $comment_stmt->close();

                                            // Display employee's information
                                            echo "<tr>
                                                <td><a href='employees-profile.php?id={$row['employee_id']}' class='text-primary fw-bold'>{$row['first_name']} {$row['last_name']}</a></td>
                                                <td>{$row['email']}</td>
                                                <td>{$row['phone']}</td>
                                                <td>{$row['applied_at']}</td>
                                                <td>
                                                    <select class='form-select progress-dropdown' data-app-id='{$row['application_id']}'>";
                                            foreach ($progress_options as $key => $value) {
                                                $selected = ($row['progress'] == $key) ? "selected" : "";
                                                echo "<option value='$key' $selected>$value</option>";
                                            }
                                            echo "</select>
                                                </td>
                                                
                                                <td>";

                                            // If comments exist, show them
                                            if (!empty($comments)) {
                                                echo "<button type='button' class='btn btn-sm btn-info' data-bs-toggle='modal' data-bs-target='#viewCommentsModal{$row['employee_id']}'>View Comments</button>
                                                    <button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#commentModal{$row['employee_id']}'>Add Comment</button>";
                                            } else {
                                                // If no comments, show Add Comment button
                                                echo "<button type='button' class='btn btn-sm btn-primary' data-bs-toggle='modal' data-bs-target='#commentModal{$row['employee_id']}'>Add Comment</button>";
                                            }

                                            echo "</td>
                                                <td>
                                                    <a href='scripts/job-update.php?id=" . $row["application_id"] . "&status=2' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to remove this application?\")'>Remove</a>
                                                </td>
                                            </tr>";

                                            // View Comments Modal
                                            if (!empty($comments)) {
                                                echo "<div class='modal fade' id='viewCommentsModal{$row['employee_id']}' tabindex='-1' aria-labelledby='viewCommentsModalLabel{$row['employee_id']}' aria-hidden='true'>
                                                        <div class='modal-dialog'>
                                                            <div class='modal-content'>
                                                                <div class='modal-header'>
                                                                    <h5 class='modal-title' id='viewCommentsModalLabel{$row['employee_id']}'>View Comments</h5>
                                                                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                                </div>
                                                                <div class='modal-body'>";
                                                foreach ($comments as $comment) {
                                                    echo "<ul><li>" . htmlspecialchars($comment['comment']) . " | <small>" . htmlspecialchars($comment['created_at']) . "</small></li></ul>";
                                                }
                                                echo "</div>
                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>";
                                            }


                                            // Add/Edit Comment Modal
                                            echo "
                                            <div class='modal fade' id='commentModal{$row['employee_id']}' tabindex='-1' aria-labelledby='commentModalLabel{$row['employee_id']}' aria-hidden='true'>
                                                <div class='modal-dialog'>
                                                    <div class='modal-content'>
                                                        <div class='modal-header'>
                                                            <h5 class='modal-title' id='commentModalLabel{$row['employee_id']}'>Add/Edit Comment</h5>
                                                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                                                        </div>
                                                        <div class='modal-body'>
                                                            <form method='POST' action='scripts/save-comment.php'>
                                                                <input type='hidden' name='job_id' value='{$job_id}'>
                                                                <input type='hidden' name='employee_id' value='{$row['employee_id']}'>
                                                                <textarea name='comment' class='form-control'></textarea>
                                                                <button type='submit' class='btn btn-primary mt-2'>Save Comment</button>
                                                            </form>
                                                        </div>
                                                        <div class='modal-footer'>
                                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center'>No employees have applied for this job yet.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <script>
                document.addEventListener("DOMContentLoaded", function() {
                    // Add event listener to each progress dropdown
                    const progressDropdowns = document.querySelectorAll('.progress-dropdown');

                    progressDropdowns.forEach(function(dropdown) {
                        dropdown.addEventListener('change', function() {
                            const appId = dropdown.getAttribute('data-app-id');
                            const progress = dropdown.value;

                            // Create a FormData object to send data via AJAX
                            const formData = new FormData();
                            formData.append('app_id', appId);
                            formData.append('progress', progress);

                            // Send AJAX request to update the progress in the database
                            fetch('scripts/update-progress.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        // Optionally, display a success message or update the UI
                                        console.log("Progress updated successfully.");
                                    } else {
                                        // Optionally, display an error message
                                        alert("Failed to update progress.");
                                    }
                                })
                                .catch(error => {
                                    console.error("Error updating progress:", error);
                                    alert("An error occurred while updating progress.");
                                });
                        });
                    });
                });
                </script>



            </div><!-- End Bordered Tabs -->

            </div>
            </div>

            </div>
            </div>
        </section>

    </main><!-- End #main -->
    <!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>