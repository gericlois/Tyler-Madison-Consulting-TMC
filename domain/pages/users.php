<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
} else {
    include "includes/head.php";
    include "../../pages/includes/connection.php";

    $admin_id = $_SESSION["admin_id"];

    $role_query = "SELECT role FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($role_query);
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("User not found.");
    }

    $user = $result->fetch_assoc();
    $user_role = $user['role']; // Will be either 'admin' or 'superadmin'

}
?>

<body>

    <!-- ======= Header ======= -->
    <?php include "includes/header.php" ?>

    <!-- ======= Sidebar ======= -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Admin Accounts
                <?php if ($user_role == 'superadmin'): ?>
                    <a href="users-add.php" class="btn btn-primary rounded-pill">
                        <i class="bi bi-plus-circle me-1"></i> Add New Admin</a>
                <?php endif; ?>
            </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                    <li class="breadcrumb-item active">Admin Accounts</li>
                </ol>
            </nav>

            <?php
            if (isset($_GET['success'])) {
                if ($_GET["success"] == "UserUpdated") {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <b>The User Admin account has been successfully updated!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
                if ($_GET["success"] == "StatusUpdated") {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <b>The User Admin Status has been successfully updated!</b>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>';
                }
                if ($_GET["success"] == "AdminAdded") {
                    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            <b>A new admin account has been successfully created!</b>
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
                            <h5 class="card-title">Admin Accounts</h5>
                            <p>Manage all admin accounts, including superadmins and standard admins.</p>



                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Admin ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT user_id, first_name, last_name, email, phone, address, role, created_at 
                                            FROM users 
                                            WHERE role IN ('admin', 'superadmin') 
                                            AND status IN (1, 2)
                                            ORDER BY user_id DESC
                                            ";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['user_id']}</td>Deactivate
                                                    <td><a href='users-profile.php?id={$row['user_id']}'>{$row['first_name']} {$row['last_name']}</a></td>
                                                    <td>{$row['email']}</td>
                                                    <td>{$row['phone']}</td>
                                                    <td>{$row['address']}</td>
                                                    <td>{$row['role']}</td>
                                                    <td>{$row['created_at']}</td>
                                                    <td>";

                                            if ($user_role == 'superadmin') {

                                                echo "<a href='scripts/user-edit.php?user_id={$row['user_id']}&status=2' 
                                                class='btn btn-sm btn-danger' 
                                                onclick='return confirm(\"Are you sure you want to deactivate this admin account?\")'>
                                                
                                               </a>";
                                            } else {
                                                echo " <a href='users-profile.php?id={$row['user_id']}' class='btn btn-sm btn-success'>View</a>";
                                            }

                                            echo "</td></tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No Admins Found</td></tr>";
                                    }
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