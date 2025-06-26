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


if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: ../users.php?error=NoUserID");
    exit();
}

$user_id = $_GET['id'];

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../users.php?error=UserNotFound");
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();
?>

<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/sidebar.php"; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Edit Admin User</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                    <li class="breadcrumb-item active">Admin User Edit</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Admin User Details</h5>
                            <form action="scripts/user-edit.php" method="POST">
                                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                                <div class="row mb-3">
                                    <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="first_name" name="first_name"
                                            value="<?php echo htmlspecialchars($user['first_name']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="last_name" name="last_name"
                                            value="<?php echo htmlspecialchars($user['last_name']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo htmlspecialchars($user['email']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="phone" name="phone"
                                            value="<?php echo htmlspecialchars($user['phone']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="address" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="address" name="address"
                                            value="<?php echo htmlspecialchars($user['address']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="birthday" class="col-sm-2 col-form-label">Birthday</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="birthday" name="birthday"
                                            value="<?php echo htmlspecialchars($user['birthday']); ?>">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="role" class="col-sm-2 col-form-label">Role</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" id="role" name="role">
                                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>
                                                Admin</option>
                                            <option value="superadmin"
                                                <?= ($user['role'] == 'superadmin') ? 'selected' : '' ?>>Super Admin</option>
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-success">Update User</button>
                                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php
                $adminCounts = [];
                $monthLabels = [];

                // Query: Count admin users by month
                $sql = "SELECT 
                MONTH(created_at) AS month, 
                COUNT(*) AS count 
                FROM users 
                WHERE role IN ('admin', 'superadmin') 
                GROUP BY MONTH(created_at) 
                ORDER BY MONTH(created_at)";
                $result = $conn->query($sql);

                // Prepare arrays for chart
                $monthNames = [1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'];
                while ($row = $result->fetch_assoc()) {
                    $monthNumber = (int)$row['month'];
                    $monthLabels[] = $monthNames[$monthNumber];
                    $adminCounts[] = (int)$row['count'];
                }
                ?>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bar Chart</h5>

                            <!-- Bar Chart -->
                            <canvas id="barChart" style="max-height: 400px;"></canvas>
                            <script>
                                document.addEventListener("DOMContentLoaded", () => {
                                    new Chart(document.querySelector('#barChart'), {
                                        type: 'bar',
                                        data: {
                                            labels: <?= json_encode($monthLabels); ?>,
                                            datasets: [{
                                                label: 'Admins Added',
                                                data: <?= json_encode($adminCounts); ?>,
                                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                                borderColor: 'rgba(54, 162, 235, 1)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    stepSize: 1
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>

                            <!-- End Bar CHart -->

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