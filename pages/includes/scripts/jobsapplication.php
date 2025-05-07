<?php
session_start();
include "../connection.php";
require_once __DIR__ . '/mailer.php'; // This should contain sendApplicationEmail()

// Check for required parameters
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: ../../promp.php?error=InvalidRequest");
    exit();
}

$job_id = intval($_GET['id']);
$status = intval($_GET['status']);
$employee_id = $_SESSION['employee_id'] ?? null;

if (!$employee_id) {
    header("Location: ../../login.php?error=NotLoggedIn");
    exit();
}

// Check for duplicate application
$sql_check = "SELECT * FROM jobapplications WHERE job_id = ? AND employee_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $job_id, $employee_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    header("Location: ../../promp.php?error=AlreadyApplied");
    exit();
}

// Insert the application
$sql_apply = "INSERT INTO jobapplications (job_id, employee_id, status) VALUES (?, ?, ?)";
$stmt_apply = $conn->prepare($sql_apply);
$stmt_apply->bind_param("iii", $job_id, $employee_id, $status);

if ($stmt_apply->execute()) {
    // Get employee + job details
    $sql_info = "
        SELECT u.email, u.first_name, u.last_name, jp.title 
        FROM employees e
        JOIN users u ON e.user_id = u.user_id
        JOIN jobpostings jp ON jp.job_id = ?
        WHERE e.employee_id = ?
    ";
    $stmt_info = $conn->prepare($sql_info);
    $stmt_info->bind_param("ii", $job_id, $employee_id);
    $stmt_info->execute();
    $result_info = $stmt_info->get_result();

    if ($row = $result_info->fetch_assoc()) {
        $employee_email = $row['email'];
        $employee_name  = $row['first_name'] . ' ' . $row['last_name'];
        $job_title      = $row['title'];

        // Send confirmation email
        sendApplicationEmail($employee_email, $employee_name, $job_title);
    }

    header("Location: ../../promp.php?success=ApplicationSubmitted");
} else {
    header("Location: ../../promp.php?error=ApplicationFailed");
}
exit();
