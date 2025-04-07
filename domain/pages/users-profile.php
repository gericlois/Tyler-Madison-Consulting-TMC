<!DOCTYPE html>
<html lang="en">
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit();
}

include "includes/head.php";
include "../../pages/includes/connection.php";

// Validate user ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color:red;'>Invalid user account.</p>";
    exit();
}

$user_id = intval($_GET['id']); // Ensure ID is an integer

// Fetch user details
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>User not found.</p>";
    exit();
}

$user = $result->fetch_assoc(); // Store user details
$stmt->close();
?>

<body>
    <!-- Header -->
    <?php include "includes/header.php" ?>

    <!-- Sidebar -->
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>User Profile</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">User Profile</li>
                </ol>
            </nav>
        </div>

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> <a href='users-edit.php?id=<?php echo htmlspecialchars($user['user_id']); ?>' class='btn btn-sm btn-warning'>Edit</a> </h2>
                            <h3><?php echo $user['role'] === 'admin' ? '<span class="badge bg-danger">Admin</span>' : '<span class="badge bg-primary">SuperAdmin</span>'; ?></h3>
                            <span><?php echo htmlspecialchars($user['created_at']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-tabs nav-tabs-bordered">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                                </li>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">Profile Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['email']); ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Phone</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['phone']); ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Address</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['address']); ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Birthday</div>
                                        <div class="col-lg-9 col-md-8"><?php echo htmlspecialchars($user['birthday']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Activity Section -->
                            <h5 class="card-title">Admin Activity</h5>

                            <!-- Table to display activity -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Activity ID</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $activity_sql = "SELECT a.activity_id, a.action, a.description, a.timestamp, u.user_id, u.first_name, u.last_name
                                    FROM activity a
                                    LEFT JOIN users u ON a.user_id = u.user_id
                                    WHERE a.user_id = $user_id
                                    ORDER BY a.timestamp DESC";

                                                            $activity_result = $conn->query($activity_sql);

                                                            if ($activity_result->num_rows > 0) {
                                                                while ($activity_row = $activity_result->fetch_assoc()) {
                                                                    $user_name = htmlspecialchars($activity_row['first_name'] . " " . $activity_row['last_name']);
                                                                    $user_id = $activity_row['user_id'];
                                                                    echo "<tr>
                                            <td>{$activity_row['activity_id']}</td>
                                            <td>{$activity_row['action']}</td>
                                            <td>{$activity_row['description']}</td>
                                            <td>{$activity_row['timestamp']}</td>
                                        </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5' class='text-center'>No activity found</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include "includes/footer.php" ?>
    <!-- Scripts -->
    <?php include "includes/scripts.php" ?>
</body>
</html>