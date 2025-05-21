<?php
session_start();
include "../../../pages/includes/connection.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employer_id = intval($_POST['employer_id']);
    $reason = trim($_POST['reason']);
    $admin_id = $_SESSION["admin_id"];
    $user_type = "employer"; // New user type

    if (empty($reason)) {
        header("Location: ../employer.php?error=NoReason");
        exit();
    }

    // Update employer status to 'blocked' (3)
    $update_sql = "UPDATE employers SET status = 3 WHERE employer_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $employer_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Insert into block_history
    $block_sql = "INSERT INTO block_history (id, blocked_by, reason, user_type) VALUES (?, ?, ?, ?)";
    $block_stmt = $conn->prepare($block_sql);
    $block_stmt->bind_param("iiss", $employer_id, $admin_id, $reason, $user_type);
    $block_stmt->execute();
    $block_stmt->close();

    // Log to activity table
    $action = "Blocked Employer";
    $description = "Blocked employer ID $employer_id for reason: '$reason'.";

    $activity_stmt = $conn->prepare("INSERT INTO activity (user_id, action, description) VALUES (?, ?, ?)");
    $activity_stmt->bind_param("iss", $admin_id, $action, $description);
    $activity_stmt->execute();
    $activity_stmt->close();

    header("Location: ../employers.php?success=EmployerBlocked");
    exit();
} else {
    header("Location: ../employers.php");
    exit();
}
