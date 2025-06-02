<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in and is either admin or employer
if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["role"], ["admin", "superadmin", "employer"])) {
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
            <h1>Employer
                <a href="employers-add.php" class="btn btn-primary rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i> Add Employer
                </a>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employer</li>
                </ol>
            </nav>
            <?php
            if (isset($_GET['success'])) {
                if ($_GET["success"] == "EmployerAdded") {
                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>A new employer has been added! Review the details of the posted employer.</b>
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
                if ($_GET["success"] == "employerUpdated") {
                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The employer has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
                if ($_GET["success"] == "StatusUpdated") {
                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The employer has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
            }
            ?>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Employer List</h5>
                            <p>Manage and view all employer in a structured table format. This section allows you to
                                track employer listings, including titles, descriptions, locations, salaries, and
                                dates. </p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Employer ID</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT * FROM `employers` where status = 1 ORDER BY employer_id DESC";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $status_text = ($row['status'] == 1) ? "Active" : "Inactive";
                                            $status_class = ($row['status'] == 1) ? "bg-primary" : "bg-dark";

                                            echo "<tr>
                                                    <td>00" . htmlspecialchars($row['employer_id']) . "</td>
                                                    <td><a href='employers-profile.php?id=" . $row['employer_id'] . "' class='text-primary fw-bold'>" . htmlspecialchars($row['name']) . "</a></td>
                                                    <td>" . htmlspecialchars($row['location']) . "</td>
                                                    <td><span class='badge $status_class'>$status_text</span></td>
                                                    <td>" . htmlspecialchars($row['created_at']) . "</td>
                                                    <td>";

                                            if ($row['status'] == 1) {
                                                echo "<a href='scripts/employer-update.php?id={$row['employer_id']}&status=2' class='btn btn-sm btn-dark' onclick='return confirm(\"Are you sure you want to make the employer Inactive?\")'>Deactivate</a>
                                                <a href='#' class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#blockModal' data-employer-id='{$row['employer_id']}'>Block</a>";
                                            } else {
                                                echo "<a href='scripts/employer-update.php?id={$row['employer_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to make the employer Active?\")'>Active</a>";
                                            }

                                            echo "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center'>No Employer Found</td></tr>";
                                    }
                                    ?>
                                </tbody>

                            </table>

                            <!-- End Table with stripped rows -->

                            <!-- Block Modal -->
                            <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="scripts/employer-block.php" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="blockModalLabel">Block Employer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="employer_id" id="blockEmployerId">
                                                <div class="mb-3">
                                                    <label for="blockReason" class="form-label">Reason for
                                                        Blocking</label>
                                                    <textarea class="form-control" name="reason" id="blockReason"
                                                        rows="4" required></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger">Block Employer</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const blockModal = document.getElementById('blockModal');

                                blockModal.addEventListener('show.bs.modal', function(event) {
                                    const button = event.relatedTarget;
                                    const employerId = button.getAttribute('data-employer-id');

                                    const inputField = document.getElementById('blockEmployerId');
                                    if (inputField && employerId) {
                                        inputField.value = employerId;
                                    }
                                });
                            });
                            </script>



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