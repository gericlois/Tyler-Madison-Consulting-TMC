<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
} else {
    include "includes/head.php";
    include "../../pages/includes/connection.php";
} ?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Employees
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Employees</li>
                </ol>
            </nav>
            <?php
            if (isset($_GET['success'])) {

                if ($_GET["success"] == "EmployeeUpdated") {
                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The employee account has been successfully updated!</b> Review the updated details to ensure accuracy.
                                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                        </div>';
                }
                if ($_GET["success"] == "StatusUpdated") {
                    echo '
                                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                            <b>The job posting has been successfully updated!</b> Review the updated details to ensure accuracy.
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
                            <h5 class="card-title">Employees</h5>
                            <p>Manage and view all employees in a structured table format. This section allows you to
                                track employee details, including names, contact information, positions, and hire dates.
                            </p>

                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Position</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT e.employee_id, u.first_name, u.last_name, u.email, u.phone, u.address, e.position, e.created_at, e.status 
                                    FROM employees e
                                    LEFT JOIN users u ON e.user_id = u.user_id
                                    WHERE e.status = 1
                                    ORDER BY e.employee_id DESC";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                        <td>00{$row['employee_id']}</td>
                                        <td><a href='employees-profile.php?id={$row['employee_id']}' class='text-primary fw-bold'>{$row['first_name']} {$row['last_name']}</a></td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['phone']}</td>
                                        <td>{$row['address']}</td>
                                        <td>{$row['position']}</td>
                                        <td>{$row['created_at']}</td>
                                        <td>
                                            <a href='scripts/feedback-request.php?employee_id={$row['employee_id']}' 
                                                    class='btn btn-sm btn-success' onclick='return confirm('Send feedback request email to this employee?')'> Request Feedback
                                                    </a>

                                           <a href='scripts/employee-update.php?id={$row['employee_id']}&status=2' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to make the employee's account inactive?\")'>Deactivate</a>

                                            <a href='#' class='btn btn-sm btn-warning' data-bs-toggle='modal' data-bs-target='#blockModal' 
                                            data-employee-id='{$row['employee_id']}'> Block
                                            </a>
                                        </td>
                                    </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No Employees found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>

                            </table>

                            <!-- Block Modal -->
                            <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="scripts/employee-block.php" method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="blockModalLabel">Block Employee</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="employee_id" id="blockEmployeeId">
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
                                                <button type="submit" class="btn btn-danger">Block Employee</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- End Table with stripped rows -->

                            <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var blockModal = document.getElementById('blockModal');
                                blockModal.addEventListener('show.bs.modal', function(event) {
                                    var button = event.relatedTarget;
                                    var employeeId = button.getAttribute('data-employee-id');
                                    blockModal.querySelector('#blockEmployeeId').value = employeeId;
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