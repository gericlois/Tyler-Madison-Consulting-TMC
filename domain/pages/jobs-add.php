<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in and is either admin or employer
if (!isset($_SESSION["admin_id"]) || !in_array($_SESSION["role"], ["admin", "superadmin", "employer"])) {
    header("Location: login.php");
    exit();
}

// Include common files
include "includes/head.php";
include "../../pages/includes/connection.php";


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
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Add Job Posting</h5>

                            <form action="scripts/job-add.php" method="POST">
                                <!-- Job Title -->
                                <div class="row mb-3">
                                    <label for="title" class="col-sm-2 col-form-label">Job Title</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="title" name="title" required
                                            placeholder="e.g., Process Validation Engineer"
                                            value="<?= $jobData ? htmlspecialchars($jobData['title']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="row mb-3">
                                    <label for="location" class="col-sm-2 col-form-label">Location</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="location" name="location" required
                                            placeholder="e.g., CO or MN Hybrid (3 days onsite)"
                                            value="<?= $jobData ? htmlspecialchars($jobData['location']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div class="row mb-3">
                                    <label for="duration" class="col-sm-2 col-form-label">Duration</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="duration" name="duration" required
                                            placeholder="e.g., 6 months+"
                                            value="<?= $jobData ? htmlspecialchars($jobData['duration']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Salary -->
                                <div class="row mb-3">
                                    <label for="salary" class="col-sm-2 col-form-label">Salary / Pay Rate</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="salary" name="salary" required
                                            placeholder="e.g., $25,000/yr or $20-50/hour"
                                            value="<?= $jobData ? htmlspecialchars($jobData['salary']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Hours per Week -->
                                <div class="row mb-3">
                                    <label for="hours" class="col-sm-2 col-form-label">Hours per Week</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="hours" name="hours" required
                                            placeholder="e.g., 40"
                                            value="<?= $jobData ? htmlspecialchars($jobData['hours']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Start Date -->
                                <div class="row mb-3">
                                    <label for="start_date" class="col-sm-2 col-form-label">Start Date</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="start_date" name="start_date" required
                                            placeholder="e.g., Immediate or July 1. 2025"
                                            value="<?= $jobData ? htmlspecialchars($jobData['start_date']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Job Description -->
                                <div class="row mb-3">
                                    <label for="description" class="col-sm-2 col-form-label">Job Description</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="description" name="description" rows="4" required
                                            placeholder="Brief summary of the role, key expectations, and team environment."><?= $jobData ? htmlspecialchars($jobData['description']) : '' ?></textarea>
                                    </div>
                                </div>

                                <!-- Responsibilities -->
                                <div class="row mb-3">
                                    <label for="responsibilities" class="col-sm-2 col-form-label">Responsibilities</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control" id="responsibilities" name="responsibilities" rows="4" required
                                            placeholder="List responsibilities like managing validations, working with R&D, conducting IQ/OQ/PQ, etc."><?= $jobData ? htmlspecialchars($jobData['responsibilities']) : '' ?></textarea>
                                    </div>
                                </div>

                                <!-- Skills / Qualifications -->
                                <div class="row mb-3">
                                    <label for="skills" class="col-sm-2 col-form-label">Skills / Qualifications</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="skills" name="skills" required
                                            placeholder="e.g., Process Validation, Supplier Quality, Manufacturing"
                                            value="<?= $jobData ? htmlspecialchars($jobData['skills']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Preferred Skills -->
                                <div class="row mb-3">
                                    <label for="preferred_skills" class="col-sm-2 col-form-label">Preferred Skills</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="preferred_skills" name="preferred_skills"
                                            placeholder="e.g., PFMEA, DFMEA, Design for Manufacturability"
                                            value="<?= $jobData ? htmlspecialchars($jobData['preferred_skills']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Education -->
                                <div class="row mb-3">
                                    <label for="education" class="col-sm-2 col-form-label">Education</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="education" name="education" required
                                            placeholder="e.g., Bachelors in Engineering or 10+ years experience"
                                            value="<?= $jobData ? htmlspecialchars($jobData['education']) : '' ?>">
                                    </div>
                                </div>

                                <!-- Employer ID -->
                                <?php if ($_SESSION["role"] === "employer" && isset($_SESSION["employer_id"])): ?>
                                    <input type="hidden" name="employer_id" value="<?= intval($_SESSION["employer_id"]) ?>">
                                <?php else: ?>
                                    <div class="row mb-3">
                                        <label for="employer_id" class="col-sm-2 col-form-label">Employer</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="employer_id" name="employer_id" required>
                                                <?php
                                                $query = "SELECT employer_id, name FROM employers ORDER BY name ASC";
                                                $result = $conn->query($query);
                                                $hasEmployers = $result->num_rows > 0;
                                                echo '<option value="">Select Employer</option>';
                                                while ($row = $result->fetch_assoc()) {
                                                    $selected = ($jobData && $jobData['employer_id'] == $row['employer_id']) ? 'selected' : '';
                                                    echo "<option value='" . $row['employer_id'] . "' $selected>" . $row['name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Hidden Inputs -->
                                <input type="hidden" id="posted_by" name="posted_by" value="<?php echo $_SESSION["admin_id"]; ?>">
                                <input type="hidden" id="status" name="status" value="<?= $jobData ? $jobData['status'] : '1' ?>">

                                <!-- Submit Button -->
                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary" <?= !$hasEmployers ? 'disabled' : '' ?>>
                                            Submit Job
                                        </button>
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