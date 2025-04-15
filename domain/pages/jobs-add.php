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

$jobData = null;
if (isset($_GET['duplicate_id'])) {
    $duplicateId = intval($_GET['duplicate_id']);
    $stmt = $conn->prepare("SELECT * FROM jobpostings WHERE job_id = ?");
    $stmt->bind_param("i", $duplicateId);
    $stmt->execute();
    $result = $stmt->get_result();
    $jobData = $result->fetch_assoc();
    $stmt->close();
}
?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Add Job Posting</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="jobs.php">Jobs</a></li>
                    <li class="breadcrumb-item active">Jobs Add Posting</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Job Posting</h5>

                            <form action="scripts/job-add.php" method="POST">
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Job Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" required
                                            value="<?= $jobData ? htmlspecialchars($jobData['title']) : '' ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Job Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            required><?= $jobData ? htmlspecialchars($jobData['description']) : '' ?></textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="location" name="location" required
                                            value="<?= $jobData ? htmlspecialchars($jobData['location']) : '' ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="salary" class="col-sm-2 col-form-label">Salary</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="salary" name="salary" step="0.01"
                                            required
                                            value="<?= $jobData ? htmlspecialchars($jobData['salary']) : '' ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="job_type" class="col-sm-2 col-form-label">Job Type</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="job_type" name="job_type" required>
                                            <option value="Full Time"
                                                <?= $jobData && $jobData['job_type'] === 'Full Time' ? 'selected' : '' ?>>
                                                Full Time</option>
                                            <option value="Part Time"
                                                <?= $jobData && $jobData['job_type'] === 'Part Time' ? 'selected' : '' ?>>
                                                Part Time</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="schedule" class="col-sm-2 col-form-label">Schedule</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="schedule" name="schedule" required
                                            value="<?= $jobData ? htmlspecialchars($jobData['schedule']) : '' ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="skills" class="col-sm-2 col-form-label">Skills</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="skills" name="skills" required
                                            value="<?= $jobData ? htmlspecialchars($jobData['skills']) : '' ?>">
                                    </div>
                                </div>

                                <input type="hidden" id="posted_by" name="posted_by"
                                    value="<?php echo $_SESSION["admin_id"]; ?>">

                                <div class="row mb-3">
                                    <label for="deadline" class="col-sm-2 col-form-label">Application Deadline</label>
                                    <div class="col-sm-10">
                                        <input type="datetime-local" class="form-control" id="deadline" name="deadline" required
                                            value="<?= $jobData && $jobData['deadline'] ? date('Y-m-d\TH:i', strtotime($jobData['deadline'])) : '' ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="employer_id" class="col-sm-2 col-form-label">Employer</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="employer_id" name="employer_id" required>
                                            <option value="">Select Employer</option>
                                            <?php
                                            $query = "SELECT employer_id, name FROM employers ORDER BY name ASC";
                                            $result = $conn->query($query);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($jobData && $jobData['employer_id'] == $row['employer_id']) ? 'selected' : '';
                                                    echo "<option value='" . $row['employer_id'] . "' $selected>" . $row['name'] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>No Employers Available</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" id="status" name="status" value="<?= $jobData ? $jobData['status'] : '1' ?>">


                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary">Submit Job</button>
                                    </div>
                                </div>
                            </form>

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