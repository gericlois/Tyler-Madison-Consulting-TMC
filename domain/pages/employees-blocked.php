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
            <h1>Blocked Employees
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Blocked Employees</li>
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
                            <h5 class="card-title">Blocked Employees</h5>
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
                                        <th>Reason</th> <!-- new column -->
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $sql = "SELECT 
                                    e.employee_id, 
                                    u.first_name, 
                                    u.last_name, 
                                    u.email, 
                                    u.phone, 
                                    u.address, 
                                    e.position, 
                                    e.created_at, 
                                    e.status,
                                    bh.reason
                                FROM employees e
                                LEFT JOIN users u ON e.user_id = u.user_id
                                LEFT JOIN (
                                    SELECT bh1.id, bh1.reason
                                    FROM block_history bh1
                                    INNER JOIN (
                                        SELECT id, MAX(blocked_at) AS latest_blocked_at
                                        FROM block_history
                                        WHERE user_type = 'employee'
                                        GROUP BY id
                                    ) bh2 ON bh1.id = bh2.id AND bh1.blocked_at = bh2.latest_blocked_at
                                    WHERE bh1.user_type = 'employee'
                                ) bh ON bh.id = e.employee_id
                                WHERE e.status = 3
                                ORDER BY e.employee_id DESC
                                ";

                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                        <td>200{$row['employee_id']}</td>
                                        <td><a href='employees-profile.php?id={$row['employee_id']}' class='text-primary fw-bold'>{$row['first_name']} {$row['last_name']}</a></td>
                                        <td>{$row['email']}</td>
                                        <td>{$row['phone']}</td>
                                        <td>{$row['address']}</td>
                                        <td>{$row['position']}</td>
                                        <td>{$row['reason']}</td>
                                        <td>{$row['created_at']}</td>
                                        <td>
                                            <a href='scripts/employees-update.php?id={$row['employee_id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Delete</a>
                                            <a href='scripts/employee-update.php?id={$row['employee_id']}&status=1' class='btn btn-sm btn-primary' onclick='return confirm(\"Are you sure you want to unblock this employee?\")'>Unblock</a>
                                        </td>
                                    </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No Blocked Employees found</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>

                            </table>

                            <!-- End Table with stripped rows -->

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