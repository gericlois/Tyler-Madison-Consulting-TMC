<?php

session_start();
include "../connection.php";


// Check if `id` (job ID) and `status` are set
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: ../../promp.php?error=InvalidRequest");
    exit();
}

$job_id = intval($_GET['id']); 
$status = intval($_GET['status']); 
$employee_id = $_SESSION['user_id'] ?? null; // Assuming user is logged in

// Ensure user is logged in
if (!$employee_id) {
    header("Location: ../../login.php?error=NotLoggedIn");
    exit();
}

// Prevent duplicate applications
$sql_check = "SELECT * FROM jobapplications WHERE job_id = ? AND employee_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $job_id, $employee_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    header("Location: ../../promp.php?error=AlreadyApplied");
    exit();
}

// Insert new application
$sql_apply = "INSERT INTO jobapplications (job_id, employee_id, status) VALUES (?, ?, ?)";
$stmt_apply = $conn->prepare($sql_apply);
$stmt_apply->bind_param("iii", $job_id, $employee_id, $status);

if ($stmt_apply->execute()) {
    header("Location: ../../promp.php?success=ApplicationSubmitted");
} else {
    header("Location: ../../promp.php?error=ApplicationFailed");
}
exit();
?>