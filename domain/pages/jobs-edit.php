<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
  header("Location: login.php");
} else {
  include "includes/head.php";
  include "../../pages/includes/connection.php";
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../jobs.php?error=NoJobID");
    exit();
}

$job_id = $_GET['id'];

$sql = "SELECT * FROM jobpostings WHERE job_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../jobs.php?error=JobNotFound");
    exit();
}

$job = $result->fetch_assoc();
$stmt->close();
?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Blank</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item">Jobs</li>
                    <li class="breadcrumb-item active">Job Edit</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Job Posting</h5>

                            <!-- Add Job -->
                            <form action="scripts/job-edit.php" method="POST">
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">

                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Job Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="<?php echo htmlspecialchars($job['title']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Job Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            required><?php echo htmlspecialchars($job['description']); ?></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="<?php echo htmlspecialchars($job['location']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="salary" class="col-sm-2 col-form-label">Salary</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="salary" name="salary" step="0.01"
                                            value="<?php echo $job['salary']; ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="deadline" class="col-sm-2 col-form-label">Application Deadline</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control" id="deadline" name="deadline"
                                            value="<?php echo date('Y-m-d\TH:i', strtotime($job['end_at'])); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="job_type" class="col-sm-2 col-form-label">Job Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="job_type" name="job_type" required>
                                            <option value="Full Time"
                                                <?php echo ($job['job_type'] == 'Full Time') ? 'selected' : ''; ?>>Full
                                                Time</option>
                                            <option value="Part Time"
                                                <?php echo ($job['job_type'] == 'Part Time') ? 'selected' : ''; ?>>Part
                                                Time</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="schedule" class="col-sm-2 col-form-label">Schedule</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="schedule" name="schedule"
                                            value="<?php echo htmlspecialchars($job['schedule']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="skills" class="col-sm-2 col-form-label">Skills</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="skills" name="skills"
                                            value="<?php echo htmlspecialchars($job['skills']); ?>" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-success">Update Job</button>
                                        <a href="../jobs.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>


                            <!-- End Edit Job -->

                        </div>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Job Posting</h5>

                            <!-- Polar Area Chart -->
                            <canvas id="polarAreaChart" style="max-height: 400px;"></canvas>
                            <script>
                            document.addEventListener("DOMContentLoaded", () => {
                                new Chart(document.querySelector('#polarAreaChart'), {
                                    type: 'polarArea',
                                    data: {
                                        labels: [
                                            'Red',
                                            'Green',
                                            'Yellow',
                                            'Grey',
                                            'Blue'
                                        ],
                                        datasets: [{
                                            label: 'My First Dataset',
                                            data: [11, 16, 7, 3, 14],
                                            backgroundColor: [
                                                'rgb(255, 99, 132)',
                                                'rgb(75, 192, 192)',
                                                'rgb(255, 205, 86)',
                                                'rgb(201, 203, 207)',
                                                'rgb(54, 162, 235)'
                                            ]
                                        }]
                                    }
                                });
                            });
                            </script>
                            <!-- End Polar Area Chart -->

                        </div>
                    </div>
                </div>


            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php include "includes/footer.php" ?>

    <!-- Vendor JS Files -->
    <?php include "includes/scripts.php" ?>

</body>

</html>