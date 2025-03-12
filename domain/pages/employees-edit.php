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
    header("Location: ../employees.php?error=NoEmployeeID");
    exit();
}

$employee_id = $_GET['id'];

$sql = "SELECT e.*, u.*, u.first_name, u.last_name, u.email
        FROM employees e
        JOIN users u ON e.user_id = u.user_id
        WHERE e.employee_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../employees.php?error=EmployeeNotFound");
    exit();
}

$employee = $result->fetch_assoc();
$stmt->close();


?>

<body>

    <?php include "includes/header.php" ?>
    <?php include "includes/sidebar.php" ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Edit Employee</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                    <li class="breadcrumb-item">Employees</li>
                    <li class="breadcrumb-item active">Employee Edit</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-6">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Edit Employee Details</h5>


                            <body>

                                <form action="scripts/employee-edit.php" method="POST">
                                    <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
                                    <input type="hidden" name="user_id" value="<?php echo $employee['user_id']; ?>">

                                    <div class="row mb-3">
                                        <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="<?php echo htmlspecialchars($employee['first_name']); ?>"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="<?php echo htmlspecialchars($employee['last_name']); ?>"
                                                required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="address" class="col-sm-2 col-form-label">Address</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="address" name="address"
                                                value="<?php echo htmlspecialchars($employee['address']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="birthday" class="col-sm-2 col-form-label">Birthday</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="birthday" name="birthday"
                                                value="<?php echo htmlspecialchars($employee['birthday']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="username" class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="<?php echo htmlspecialchars($employee['username']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="role" class="col-sm-2 col-form-label">Role</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="job_seeker"
                                                    <?= ($employee['role'] == 'job_seeker') ? 'selected' : '' ?>>Job
                                                    Seeker</option>
                                                <option value="employee"
                                                    <?= ($employee['role'] == 'employee') ? 'selected' : '' ?>>Employee
                                                </option>
                                                <option value="admin"
                                                    <?= ($employee['role'] == 'admin') ? 'selected' : '' ?>>Admin
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="position" class="col-sm-2 col-form-label">Position</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="position" name="position"
                                                value="<?php echo htmlspecialchars($employee['position']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-sm-10 offset-sm-2">
                                            <button type="submit" class="btn btn-success">Update Employee</button>
                                            <a href="../employees.php" class="btn btn-secondary">Cancel</a>
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