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
?>

<body>
<?php include "includes/header.php"; ?>
<?php include "includes/sidebar.php"; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Duplicate Job Posting</h1>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body pt-4">
                        <form method="GET" action="jobs-add.php">
                            <div class="mb-3">
                                <label for="job_id" class="form-label">Select a Job to Duplicate</label>
                                <select name="duplicate_id" id="job_id" class="form-control" required>
                                    <option value="">-- Select Job --</option>
                                    <?php
                                    $jobs = $conn->query("SELECT job_id, title FROM jobpostings ORDER BY posted_at DESC");
                                    while ($job = $jobs->fetch_assoc()) {
                                        echo "<option value='{$job['job_id']}'>{$job['title']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Duplicate Job</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include "includes/footer.php"; ?>
<?php include "includes/scripts.php"; ?>
</body>
</html>
