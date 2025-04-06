<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = intval($_POST['employee_id']);
    $reason = trim($_POST['reason']);
    $admin_id = $_SESSION["admin_id"];

    if (empty($reason)) {
        header("Location: ../employees.php?error=NoReason");
        exit();
    }

    // Update employee status to 'blocked' (3)
    $update_sql = "UPDATE employees SET status = 3 WHERE employee_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $employee_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Insert into block_history
    $block_sql = "INSERT INTO block_history (employee_id, blocked_by, reason) VALUES (?, ?, ?)";
    $block_stmt = $conn->prepare($block_sql);
    $block_stmt->bind_param("iis", $employee_id, $admin_id, $reason);
    $block_stmt->execute();
    $block_stmt->close();

    // Log to activity table
    $action = "Blocked Employee";
    $description = "Blocked employee ID $employee_id for reason: '$reason'.";

    $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
    $activity_stmt->bind_param("iss", $admin_id, $action, $description);
    $activity_stmt->execute();
    $activity_stmt->close();

    header("Location: ../employees.php?success=EmployeeBlocked");
    exit();
} else {
    header("Location: ../employees.php");
    exit();
}
